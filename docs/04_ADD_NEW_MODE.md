# 04_ADD_NEW_MODE｜新增模式 / gametype / instance 实战指南

> 一句话摘要：先明确“只改规则（gtype）”还是“整包玩法（instance）”，再按注册、逻辑、配置、UI 四层落地。

## 适合谁看
- 想新增模式、改模式规则、做活动服的人。

## 建议先读什么
- `docs/02_MODULE_SYSTEM.md`
- `include/modules/extra/instance/gtype1/main/main.php`
- `include/modules/extra/instance/instance10_rogue/main.php`

## 关键文件列表
- `include/modules/core/sys/config/gamemode.config.php`
- `include/modules/extra/instance/**`
- `gamedata/modules.list.php`
- （若涉及房间）`include/roommng/**`

---

## 1) `gtypeX` 与 `instanceX` 的关系（经验总结）

### `gtypeX` 更像“规则补丁层”
- 典型做法：在大量公共函数中 `if ($gametype == X)` 分支替换行为。
- 例子：`gtype1/main` 修改选卡、NPC、商店、连斗、结算等。

### `instanceX` 更像“完整玩法包”
- 典型做法：附带专属 `config`、专属技能、甚至剧情/NPC链路。
- 例子：`instance10_rogue` 除了规则替换，还带多技能（961~963 等）、商人/解锁流程。

> 实务上两者可以叠加：同局既有 `gametype` 条件分支，也可加载 instance 模块。

---

## 2) 新增一个模式，最小需要改哪些文件

### A. 注册层（必须）
1. 新建模块目录：`include/modules/extra/instance/<your_mode>/`
2. 写 `module.inc.php`（依赖、codelist、templatelist）
3. 在 `gamedata/modules.list.php` 增加模块行并启用 `,1`

### B. 逻辑层（必须）
- 在 `main.php` 覆写你要接管的钩子函数，常见：
  - `get_npclist()`
  - `get_shopconfig()`
  - `get_itemfilecont()/get_trapfilecont()`
  - `checkcombo()`
  - `check_addarea_gameover()`
  - `rs_game()`

### C. 配置层（常见）
- `config/npc.data.config.php`
- `config/shopitem.config.php`
- `config/mapitem.config.php`
- `config/stitem.config.php`、`stwep.config.php`

### D. UI/模板层（可选）
- 新增 `*.htm`，并在 `templatelist` 注册。

---

## 3) 三个现有模式对照

## 3.1 低复杂度：`gtype5/main`
特点：主要替换资源入口（npc/shop/mapitem/trap/stitem/stwep），逻辑改动较少。

## 3.2 中复杂度：`gtype1/main`
特点：
- 改入场卡规则（强制卡 93）；
- 改遭遇、隐蔽、组队行为；
- 改结算与排名奖励；
- 改 `prepare_new_game()` 触发条件。

## 3.3 高复杂度：`instance10_rogue`
特点：
- 改选卡、初始道具、地图显示、商店机制；
- 禁区节奏和结束条件重写；
- 合成结果动态变异；
- 配套大量专属技能与配置文件。

---

## 4) 新增模式 Checklist（可直接照抄）
1. [ ] 明确模式编号（`gametype` 值）与适用房间。
2. [ ] 新建模块并写 `module.inc.php`。
3. [ ] 在 `main.php` 只覆写必要钩子，统一加 `if ($gametype==X)` 保护。
4. [ ] 准备配置文件（npc/shop/mapitem/...）。
5. [ ] 若有专属技能，新增 skill 模块并加入 modules.list。
6. [ ] 若要改选卡，覆写 `card_validate_*` 或 `get_enter_battlefield_card`。
7. [ ] 若要改胜负条件，覆写 `check_addarea_gameover` / 相关 gameover 流程。
8. [ ] 更新文档和测试脚本。
9. [ ] ADV/daemon 环境重新编译模块缓存。

## 新人最容易踩的坑
- 只改了 `gtype` 但忘了模式入口在哪里设置 `gametype`。
- 配置文件路径写错（`__DIR__.'/config/...'` 最稳）。
- 没在 `modules.list` 启用，代码永远不执行。
- 忘记处理模式退出/结算路径，导致“能开不能收尾”。

## 待确认
- 部分房间模式切换逻辑位于房间管理模块，新增模式是否需要 UI 侧开放入口需人工联调确认。
