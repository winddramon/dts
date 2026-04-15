# 07_ADD_NEW_MECHANIC｜新增“跨模块机制”的方法

> 一句话摘要：新机制优先做成独立模块，通过钩子串联 battle/item/map/player/UI，避免把逻辑散落在旧模块里。

## 适合谁看
- 要做新玩法系统（非单卡/单技能）的开发者。

## 建议先读什么
- `docs/02_MODULE_SYSTEM.md`
- `docs/04_ADD_NEW_MODE.md`
- 本文案例章节

## 关键文件列表
- `include/modules/extra/card/cardbase/main.php`
- `include/modules/extra/club/clubbase/main.php`
- `include/modules/extra/instance/instance10_rogue/main.php`
- `include/modules/core/sys/gamectl.php`

---

## 1) 什么时候该新建模块，而不是塞进旧模块
建议新建模块的信号：
1. 需要跨 2 个以上子系统（如战斗 + 道具 + UI）。
2. 机制只在某些模式生效，不应污染所有模式。
3. 需要独立配置与后续迭代空间。
4. 需要开关化（能在 `modules.list` 启停）。

反例：
- 只改一个数值常量，且仅本模块使用，不必新建模块。

---

## 2) 跨模块机制剖析（现有案例）

### 案例 A：卡片机制（`cardbase`）
跨越：
- 入场流程（`valid.func.php`）
- 用户数据（`card_data` 编码）
- 模式禁卡（`card_validate_*` + gtype 覆写）
- 技能系统（`skills` 注入）

为什么是独立模块：
- 涉及规则、资源、UI、存储、模式兼容，耦合面太广。

### 案例 B：肉鸽机制（`instance10_rogue`）
跨越：
- 地图可见性与区域颜色
- 商店入口替换
- 合成结果变异
- 开局资源/NPC/结局判定
- 卡片强制策略

为什么合理：
- 通过 `if ($gametype==20)` 收口在一个 instance 模块，避免污染基础玩法。

---

## 3) 推荐决策流程（新增机制）

### Step 1：先找可挂钩函数
先在以下关键词搜：
- `act`, `strike_prepare`, `itemmix_success`, `check_addarea_gameover`, `parse_interface_*`, `get_*config`。

### Step 2：判断挂在哪层
- 涉及全局流程与 gameinfo：偏 `core`/`sys`。
- 常规玩法共享：偏 `base`。
- 特定模式/活动：偏 `extra`（推荐）。

### Step 3：确定边界
- 规则判定放机制模块；
- 资源表放 `config/*.php`；
- 展示放 `*.htm`；
- 模式判断统一收口，不要散落多个 unrelated 模块。

### Step 4：落地顺序
1. 空模块 + `module.inc.php`
2. 最小钩子闭环（能跑）
3. 配置拆分
4. UI 与日志
5. 调优与兼容

---

## 4) 如何避免逻辑散落
- 所有 `if($gametype==X)` 尽量集中在同一机制模块（或同一目录族）。
- 明确“单一数据源”：不要同一状态既写 `$gamevars` 又写临时全局再写 DB。
- 给机制建一个“主入口函数”（例如 `mechanicX_process()`）供多处调用。

## 新人最容易踩的坑
- 先到处补丁，最后自己都找不到机制入口。
- 忽略 `save_gameinfo()/player_save()`，导致状态只在当前请求有效。
- UI 逻辑直接写在主流程，不可复用且难测。

## 待确认
- 某些旧机制是否仍依赖历史页面脚本短路分支，建议在联调中补全调用链图。
