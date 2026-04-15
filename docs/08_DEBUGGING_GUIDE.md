# 08_DEBUGGING_GUIDE｜排错与修 BUG 指南

> 一句话摘要：先判断故障层（入口/模块/配置/daemon），再看对应目录，最后才怀疑业务逻辑本身。

## 适合谁看
- 接盘修线上 bug 的维护者。

## 建议先读什么
- `docs/01_RUNTIME_FLOW.md`
- `docs/03_DIRECTORY_MAP.md`

## 关键文件列表
- `command.php`
- `include/common.inc.php`
- `include/pages/command_act.php`
- `include/modules/core/sys/*`
- `gamedata/tmp/server/*`

---

## 1) 第一轮分诊：四层法

## A. 入口层问题（请求没进业务）
看：
- `command.php` 分支是否命中。
- `$page/$mode/$command` 是否正确。
- 是否被 `redirect:` 或 `gexit()` 提前返回。

## B. 模块层问题（逻辑没按预期）
看：
- 同名函数链是否被其他模块覆盖。
- `modules.list.php` 顺序与启用状态。
- 目标模块是否真的在链上（`MOD_XXX`）。

## C. 配置层问题（逻辑对，但数据错）
看：
- `config/*.php` 是否与模式匹配。
- 选卡/技能/道具配置是否被模式禁用。
- 样本配置与本地真实配置是否一致。

## D. daemon 层问题（业务像错，实际是进程通信错）
看：
- `gamedata/tmp/server/<port>/busy/start_time/worknum`。
- 是否存在“无可用进程 / 进程异常 / 回包文件缺失”。
- 日志级别是否允许输出足够信息。

---

## 2) “这是业务 bug 还是 daemon bug”快速判断

### 高概率 daemon 问题特征
- 同一请求偶发失败、重试又好。
- 页面空白或拿到旧回包。
- `command.php` client 日志显示找不到可用 server。
- `busy` 文件长期不消失。

### 高概率业务问题特征
- 特定模式/特定技能必现。
- 关闭 daemon 后仍稳定复现。
- 数据库状态与预期逻辑持续偏离。

---

## 3) 各类异常优先看哪里

## 页面显示异常
1. `include/pages/command_*.php`
2. 对应模块 `parse_interface_*` / `prepare_response_content`
3. `templates/default/*.htm`
4. `gamedata/templates/*.tpl.php`（确认是否编译旧缓存）

## 玩家数据异常
1. `core/player/main.php`（fetch/load/save/锁）
2. `valid.func.php`（入场初始）
3. `core/sys/gamectl.php`（gameover/reset）

## 战斗/技能/卡片异常
1. `base/battle`, `base/attack`, `skillbase`
2. 对应 `skillXXX/main.php`
3. `cardbase/main.php` + `card.config.php`

## 模式异常
1. 对应 `gtypeX/main.php` 或 `instanceX/main.php`
2. `gamemode.config.php`
3. 该模式专属 `config/*.php`

---

## 4) 缓存/临时文件处理建议

### 运行时产物（可清理，谨慎）
- `gamedata/tmp/*`：锁、socket、回包、房间状态。
- `gamedata/run/*`、`gamedata/modinit/*`：ADV 产物（可重建）。
- `gamedata/templates/*`：模板编译产物。

### 不应作为源码阅读重点
- `gamedata/tmp`
- `gamedata/cache`（除非排缓存问题）

---

## 5) 常见“伪业务 bug”场景
1. **改模块后无效**：未重新编译 ADV 产物。
2. **模式逻辑错乱**：daemon 驻留进程尚未重载到新代码。
3. **选卡异常**：`card_validate` 与 gtype 强制禁卡叠加。
4. **房间状态异常**：`roomvars`/room 文件与 game 表不同步。
5. **并发复活/状态错位**：玩家锁/进程锁释放异常。

---

## 6) 推荐排障顺序（可贴工单）
1. 记录复现步骤 + `gametype/roomid/command`。
2. 判定是否 daemon 模式。
3. 看 `command.php` 实际命中分支。
4. 看目标函数的模块链覆盖顺序。
5. 验证关键状态是否持久化（DB/gamevars/player_save）。
6. 必要时清理运行时产物并重编译后复测。

## 具体示例
- 现象：玩家点击按钮后偶发“无响应”，刷新后恢复。  
  先看 `gamedata/tmp/server/*/busy` 是否长期存在，再看 `command.php` client 分支是否选到可用进程。
- 现象：某技能仅在特定模式异常。  
  先查 `skillXXX/main.php`，再查对应 `gtype/instance` 是否覆写了同名钩子导致行为变化。

## 新人最容易踩的坑
- 一上来就改业务逻辑，不先判断 daemon/缓存状态。
- 看到报错就改 `include/modules`，但实际运行代码来自 `gamedata/run` 旧产物。

## 待确认
- 线上日志收集方式（文件/外部系统）在仓库内未统一体现，建议运维侧补一份 SOP。
