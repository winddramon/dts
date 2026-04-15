# 09_LLM_ONBOARDING｜给后续 AI 助手的高密度上手说明

> 一句话摘要：这个仓库是“模块链 + daemon 转发 + 编译产物”的系统，不是普通 include 单体。

## 适合谁看
- 后续接手本仓库的 LLM / Agent。

## 建议先读什么（严格顺序）
1. `command.php`
2. `include/common.inc.php`
3. `include/modules/modules.func.php`
4. `gamedata/modules.list.php`
5. `include/pages/command_act.php`
6. `include/modules/extra/card/cardbase/{main.php,config/card.config.php}`
7. 目标模式目录（`extra/instance/...`）

## 关键文件列表
- 模块装配：`gamedata/modules.list.php`
- 模块运行机制：`include/modules/modules.func.php`
- 入场与卡片：`include/valid.func.php`, `command_valid.php`
- 请求主链：`command.php`, `common.inc.php`

---

## 给 AI 的硬规则
1. 搜函数时默认“同名多实现”，不要只看第一处命中。
2. 看到 `$chprocess(...)` 说明在钩子链中，必须追踪上下游。
3. 改模块后若无效，优先怀疑 ADV/daemon 缓存，而非代码没执行。
4. `gamedata/tmp` 是运行态，不是业务源码。
5. `config.sample.php` 不是线上实际值，真实配置可能在被忽略文件里。

---

## 任务类型 → 首查位置

### 新卡片
1. `card.config.php` 的 `$cards`
2. `cardbase::card_valid_info_process`
3. `card_validate*`
4. `valid.func.php::enter_battlefield`
5. 对应 `skillXXX` 模块（若有）

### 新技能
1. 选择目录（base/card/club/instance）
2. 新建 `skillXXX/{module.inc.php,main.php}`
3. 接入触发源（card/club/mode/item）
4. 确认 skillbase 参数读写
5. modules.list 启用

### 新模式
1. 选择 `gtypeX` or `instanceX`
2. 覆写 `get_*config/checkcombo/check_addarea_gameover/rs_game`
3. 加配置文件
4. 注册模块
5. 测试结算路径

### 修 BUG
1. 判入口层 or daemon层
2. 判模块链覆盖
3. 判配置数据
4. 判持久化（save_gameinfo/player_save）
5. 清理缓存并重编译复测

---

## 常见误区（AI 高发）
- 把 `gamedata/run` 改成“源文件”。
- 忽略 `gametype` 条件，导致全模式被改。
- 在 daemon 模式下输出调试文本破坏回包协议。
- 只改 `module.inc.php` 不更新 `modules.list`。

## 具体示例
- 任务“新增卡片技能”：若只改 `card.config.php` 而没启用 `skillXXX` 模块，入场时会拿不到行为效果。
- 任务“修复某模式商店异常”：先查 `gtype/instance` 对 `get_shopconfig()` 的覆写，而不是先改 `base/itemshop`。

## 新人最容易踩的坑
- AI 只依据目录名推断，不跟踪 `modules.list` 实际启用情况。
- AI 忽略 `$chprocess()` 导致误判调用顺序。

## 待确认
- 当前部署是否强制 daemon 常驻与自动拉起（需看运维脚本/进程管理）。
