# 03_DIRECTORY_MAP｜目录地图（按“什么时候去看”组织）

> 一句话摘要：优先区分“源码区/编译产物区/运行时区”，再按功能去找模块。

## 适合谁看
- 不知道该从哪个目录入手的维护者。

## 建议先读什么
- `docs/02_MODULE_SYSTEM.md`
- 本文第 1、2、3 章

## 关键文件列表
- `include/modules/**`
- `include/pages/**`
- `gamedata/**`
- `templates/default/**`

---

## 1) 顶层目录速览

- `include/`：核心 PHP 源码（主战场）。
- `gamedata/`：SQL、缓存、运行时、编译结果、录像与临时文件。
- `templates/`：模板源码（默认包在 `templates/default`）。
- 根目录脚本（`game.php`, `command.php`, `index.php`）：入口层。

---

## 2) `include/modules` 子体系

### `core/`（底座/框架层）
什么时候看：排“全局流程、玩家/地图基础状态、系统 routine、输入处理”问题。
- `core/input`：输入变量注入。
- `core/sys`：全局状态、gameinfo 读写、游戏开局/结束流程。
- `core/player`：玩家数据池、锁、加载/保存。
- `core/map`：地图与禁区基础。
- `core/gameflow/*`：流程状态机（连斗、死斗、反挂机等）。

### `base/`（基础玩法能力层）
什么时候看：排“战斗/道具/探索/武器/常规技能”问题。
- `base/battle`, `base/attack`, `base/weapon/*`
- `base/itemmain`, `base/itemmix/*`, `base/items/*`
- `base/skills/skill*`
- `base/npc`, `base/explore`, `base/itemshop`

### `extra/`（扩展玩法层）
什么时候看：排“模式差异、活动、特殊系统”问题。
- `extra/card/`：卡片系统与卡牌技能。
- `extra/club/`：称号系统与称号技能。
- `extra/instance/`：模式/实例（gtypeX + instanceX）
- `extra/activities/`：活动类模块。
- `extra/achievement/`：成就与奖励技能。
- `extra/misc/`：杂项增强（bufficons、回放、天梯等）。

---

## 3) 重点子目录说明

### `extra/card/`
- `cardbase/`：卡配置、校验、入场卡效果应用、抽卡与能量。
- `skills/skillXXX/`：卡片绑定技能。
- `kujibase/`：抽卡相关入口（与 `kuji.php` 相关）。

### `extra/club/`
- `clubbase/`：称号可选列表、称号技能挂载、战斗技入口。
- `clubs/clubX/`：称号本身定义。
- `skills/skillXX/`：称号技能实现。

### `extra/instance/`
- `gtypeX/`：按 `gametype` 条件改写核心行为（偏“模式规则层”）。
- `instanceX_.../`：具体实例玩法（偏“完整玩法包”）。
- 常见包含 `config/*.php` + 专属 skill 模块。

### `skills` / `attr` / `misc`（横切逻辑）
- `skills`：功能效果逻辑。
- `attr`：道具属性或状态标签扩展。
- `misc`：不归入主玩法的辅助机制（性能、显示、记录等）。

---

## 4) `include/pages/` 页面脚本层

什么时候看：
- 页面输出异常、重定向异常、页面级逻辑（非模块钩子）问题。

关键：
- `command_act.php`：动作执行回包。
- `command_game.php`：游戏页初始化。
- `command_valid.php`：入场与选卡页面逻辑。
- `command_news/help/end/rank...`：各功能页。

---

## 5) `gamedata/`（必须按“源码/产物”分开看）

### 源码/配置类（可读）
- `gamedata/modules.list.php`：模块启用清单。
- `gamedata/sql/`：数据库结构和重置脚本。

### 编译产物（可删可再生）
- `gamedata/modinit/`：模块 init 产物。
- `gamedata/run/`：ADV2 预处理运行代码。
- `gamedata/templates/`：模板编译产物。

### 运行时（排障关注，不当源码读）
- `gamedata/tmp/server/`：daemon 端口目录、busy/start_time 等。
- `gamedata/tmp/response/`：socket 回包缓存。
- `gamedata/tmp/playerlock/`：玩家锁。
- `gamedata/tmp/news/`：news 刷新标记。
- `gamedata/tmp/rooms/`：房间运行态。

---

## 6) `templates/default/`
什么时候看：
- UI 显示错位、按钮缺失、前端结构异常。

注意：
- 编辑这里是改“模板源码”；
- 实际运行可能走 `gamedata/templates/*.tpl.php` 编译文件；
- 若模板改了没生效，检查模板缓存与编译时间戳。

## 新人最容易踩的坑
- 把 `gamedata/run` 直接当手改源文件（会被重新生成覆盖）。
- 只改 `templates/default`，没意识到编译缓存仍是旧版本。
- 在 `extra` 修 bug 时忘了 `gametype` 分支条件，误伤标准模式。

## 待确认
- 某些历史 `gamedata/bak`/`gamedata/replays` 运维策略依赖外部清理脚本，需部署侧确认。
