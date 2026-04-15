# 06_ADD_NEW_SKILL｜技能系统与加技能流程

> 一句话摘要：技能是模块；技能 ID 是路由键；真正的触发时机由各上游模块钩子调用。

## 适合谁看
- 想新增/改技能（称号、卡片、实例技能）的人。

## 建议先读什么
1. `include/modules/base/skillbase/`（技能底层）
2. 任意 skill 模块（如 `skill1`, `skill71`, `skill731`）
3. `clubbase/main.php` / `cardbase/main.php`

## 关键文件列表
- `include/modules/base/skills/skill*/`
- `include/modules/extra/card/skills/skill*/`
- `include/modules/extra/club/skills/skill*/`
- `include/modules/extra/instance/**/skill*/`
- `gamedata/modules.list.php`

---

## 1) 技能模块分布规律
- `base/skills`：基础通用技能。
- `extra/card/skills`：卡片相关技能。
- `extra/club/skills`：称号技能。
- `extra/instance/.../skill*`：模式专属技能。

## 2) 技能 ID 组织约定（观察结论）
- 基础技能多为小号（1~几十）。
- 称号、卡片、实例、活动逐渐占用更高段位。
- 强约束来自模块命名：`skill731` 对应 namespace 与文件夹名都应一致。

---

## 3) 一个新技能通常需要哪些文件
最小集：
- `module.inc.php`
- `main.php`

常见附加：
- `desc.htm`（技能描述）
- `profilecmd.htm` / `battlecmd_desc.htm`（UI）
- `config/*.php`（复杂参数）

---

## 4) 如何被外部引用

### 从卡片引用
在 `card.config.php`：
```php
'valid' => ['skills' => ['731' => '0']]
```

### 从称号引用
`clubbase` 给称号时会遍历 `clublist[club]['skills']` 并 `skill_acquire`。

### 从模式/道具引用
在 `main.php` 中调用 `skill_acquire/skill_setvalue`，或在卡/道具效果里写入 skill 列表。

---

## 5) 技能参数传递方式
- 常量信息：`define('MOD_SKILLXXX_INFO','club;battle;...')`
- 动态值：`skill_setvalue($id, $key, $val, $pa)`
- 查询：`skill_query`, `skill_getvalue`
- 复杂状态：可用 `gencode/gdecode` 序列化结构（如交易记录、多层数组）

---

## 6) 三种风格技能对比

### A. 简单被动：`skill1`
- 结构简洁；
- 主要覆写若干计算函数；
- 无复杂 UI/状态。

### B. 主动战斗技：`skill71`
- 含怒气消耗、解锁判定、战斗前准备；
- 改伤害倍率并写 news；
- 在 `clubbase` 战斗技入口中被调用。

### C. 复杂交互技：`skill731`
- 含市场状态、全局 `gamevars`、买卖操作、库存与价格更新；
- 含界面模板和记录序列化；
- 体现“技能可承载系统级玩法”。

---

## 7) 新增技能 Checklist
1. [ ] 选定未冲突 skill id。
2. [ ] 新建 `skillXXX/module.inc.php + main.php`。
3. [ ] 每个可覆写函数开头加 `__MAGIC__`。
4. [ ] `init()` 内注册技能名与信息。
5. [ ] 接入触发源（卡/称号/模式/道具）。
6. [ ] 如需 UI，补 `desc/profilecmd` 并加到 `templatelist`。
7. [ ] 加入 `gamedata/modules.list.php`。
8. [ ] 编译并验证：获取技能、触发技能、保存/重载后状态正确。

## 新人最容易踩的坑
- 只写了技能模块，没挂接到任何入口，导致“永远不触发”。
- 参数存进 `$pa` 临时数组却没通过 `player_save` 持久化。
- 忘记在非当前玩家对象上调用时传 `$pa`，误改了 `$sdata`。

## 待确认
- 个别历史技能仍依赖旧式字段编码，建议逐个验证存档兼容性。
