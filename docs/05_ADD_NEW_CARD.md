# 05_ADD_NEW_CARD｜卡片系统与加卡流程

> 一句话摘要：卡片以 `card.config.php` 为中心，入场时由 `cardbase::enter_battlefield_cardproc()` 将配置转成玩家初始数据/技能。

## 适合谁看
- 要新增卡片、改卡池、改选卡限制的人。

## 建议先读什么
1. `include/modules/extra/card/cardbase/config/card.config.php`
2. `include/modules/extra/card/cardbase/main.php`
3. `include/valid.func.php`

## 关键文件列表
- `cardbase/module.inc.php`
- `cardbase/main.php`
- `cardbase/config/card.config.php`
- `include/pages/command_valid.php`
- `include/valid.func.php`

---

## 1) `cardbase` 的职责边界
- 管理卡片定义、卡包、稀有度、能量/CD。
- 提供选卡可用性校验（`card_validate*`）。
- 在入场时应用卡效果（`enter_battlefield_cardproc`）。
- 管理“拥有卡数据”与 `card_data` 编码存储。

不负责：
- 具体技能行为细节（由 skill 模块实现）。

---

## 2) `card.config.php` 关键结构

### 卡包与稀有度
- `$packlist`, `$packdesc`, `$packstart`, `$packicon`
- `$cardindex`（运行时可由 cache 覆盖）
- `$card_rarecolor`, `$card_price`, 闪碎概率等

### `$cards` 主体
每张卡通常是：
```php
$id => [
  'name' => ...,
  'rare' => 'S/A/B/C/M',
  'pack' => ...,
  'energy' => ...,
  'valid' => [...]
]
```

### `valid` 常见字段
- 直接属性：`att/def/hp/sp/lvl/...`
- 装备/道具：`wep*`, `arb*`, `itm*`
- `skills`：直接挂技能
- `cardchange`：随机发动其他卡
- `rand_sets`：随机套装（多组配置随机一组）
- `rand_skills`：随机技能组
- `gamevars`：修改全局游戏变量

---

## 3) 纯配置卡 vs 需要技能模块的卡

### 纯配置卡（不新建技能）
只改初始属性/道具/称号即可，例如：
- 直接给武器、给数值、给固定 club。

### 绑定技能卡（需要技能模块）
卡效果涉及“回合中触发/战斗钩子/状态持续”，就应新建 skill 模块，再在 `valid.skills` 引用 skill id。

---

## 4) 入场链路（卡片如何生效）
1. `command_valid.php` 选卡并做可用性检查。
2. `enter_battlefield()`（`include/valid.func.php`）调用 `cardbase::get_enter_battlefield_card()`。
3. `enter_battlefield_cardproc()` 读取卡 `valid`。
4. `card_valid_info_process()` 把 `valid` 映射到玩家初始数据 + skills 列表。
5. 玩家入库后，skillbase 处理技能获得。

---

## 5) 典型范例（仓库内）

### 范例 A：纯数据卡
如很多基础 C 卡：仅给道具/属性，不需要新模块。

### 范例 B：绑定技能卡
如带 `'skills' => ['731' => '0']` 的卡，依赖 `extra/card/skills/skill731`。

### 范例 C：随机换卡卡
如配置 `valid.cardchange` 的卡（按概率随机抽 S/A/B/C）。

### 范例 D：模式专用卡
如在某 gametype 下被强制选中（`gtype1` 强制 93，`instance10` 强制 1002）。

---

## 6) 卡池索引 / 缓存要不要更新
- `card.config.php` 是源定义。
- 运行时可能读 `gamedata/cache/card_index.config.php` 覆盖 `$cardindex`。
- 若改了卡池或抽卡规则，需确认缓存是否需要重建（以及 modulemng 编译）。

---

## 7) 两套落地步骤

### 7.1 新增一张简单卡（纯配置）
1. 在 `$cards` 新增编号与定义。
2. 设定 `pack`、`rare`、`energy`、`valid`。
3. 若要可抽到，确认对应 rarity 的 index 生成逻辑。
4. 验证 `card_validate` 下是否被当前模式禁用。
5. 进场测试：看 `enter_battlefield_cardproc` 是否注入预期字段。

### 7.2 新增一张复杂卡（独特效果）
1. 先新建 `extra/card/skills/skillXXX/` 模块。
2. 在 `module.inc.php` 声明依赖与模板。
3. 在 `main.php` 实现钩子逻辑。
4. `cards[id].valid.skills` 挂上 skill id。
5. 若有随机/变卡逻辑，增加 `cardchange` / `rand_sets` / `rand_skills`。
6. 加入 `gamedata/modules.list.php` 并重新编译。

## 新人最容易踩的坑
- 只改 `$cards`，忘了 skill 模块没启用。
- `rand_skills` 下 `rnum`/数组索引写错，导致抽不到技能。
- 以为 `cardindex` 手写生效，实际被 cache 覆盖。
- 忘记模式禁卡逻辑（`card_validate_get_forbidden_cards` + 各 gtype 覆写）。

## 待确认
- 当前线上卡池索引重建流程是否全自动（与运营脚本有关，需部署侧确认）。
