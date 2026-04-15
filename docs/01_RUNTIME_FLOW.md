# 01_RUNTIME_FLOW｜请求链路与运行时流程

> 一句话摘要：从浏览器到 `command.php`，再到 `common.inc.php` 和模块链，最终落到 `include/pages/command_*.php`。

## 适合谁看
- 需要排查“请求到底跑到了哪里”的维护者。

## 建议先读什么
- `command.php`
- `include/common.inc.php`
- `include/pages/command_act.php`
- `include/pages/command_game.php`

## 关键文件列表
- Web 入口：`game.php`、`index.php`、`chat.php` 等顶层脚本。
- 命令中心：`command.php`。
- 初始化：`include/common.inc.php`。
- 页面脚本：`include/pages/command_*.php`。

---

## 1. Web 入口在哪里

### 游戏页入口（展示页）
`game.php` 做了两件事：
1. 载入 `server.config.php` 以读取站点配置；
2. `echo render_page('command_game')`，最终走到 `command.php` 的 `page=command_game` 分支。

### 命令入口（AJAX）
前端实际动作（移动、攻击、道具等）会命中 `command.php`，默认走 `command_act.php`。

---

## 2. `command.php` 的职责分层

### A. daemon server 启动路径（`$_POST['command']='start'`）
当传入连接密码和 `start` 时：
- 定义 `IN_DAEMON`；
- 立即返回 HTTP 200 给调用端，然后自己继续后台驻留；
- 绑定本地随机端口，写 `gamedata/tmp/server/<port>/` 状态文件；
- 循环监听 socket，请求到来后 `include ./command.php` 再执行业务分支。

### B. client 转发路径（浏览器来请求，但当前脚本非 daemon 内部调用）
开启 `$___MOD_SRV` 时：
- 先 `NO_MOD_LOAD + NO_SYS_UPDATE` 轻量加载 `common.inc.php`；
- 在 `gamedata/tmp/server` 选择可用 daemon；
- 通过 `__SOCKET_SEND_TO_SERVER__()` 把请求发给 daemon；
- 读取 daemon 结果返回浏览器。

### C. 非 daemon 直跑路径（`$___MOD_SRV` 关闭）
直接 `require include/common.inc.php`，然后进入页面分支执行。

---

## 3. 页面请求和命令请求如何分流

`command.php` 末尾按 `$page` 分流：
- `!isset($page) || $page=='command'` → `include/pages/command_act.php`（核心动作）
- `page=command_game` → `include/pages/command_game.php`（进入游戏页初始渲染）
- 其他 `command_valid/command_end/command_help/...` → 对应页面脚本。

这解释了为什么“同一个 command.php”能同时服务动作与各种子页面。

---

## 4. `sys::routine()` 在什么阶段发生

### 常规流程
`include/common.inc.php` 在模块加载后会触发：
- 若非 `chat/help`，且非 `news(sendmode=news)`，执行 `\sys\routine()`。

### daemon 特判
daemon 主循环里也有一次判断：
- 非聊天新闻刷新（`sendmode != news`）时执行 `\sys\routine()`。

> 实际效果：绝大多数请求在进业务逻辑前都先刷新全局游戏状态。

---

## 5. 玩家数据、地图数据、模板渲染在哪一层

- 玩家数据：`player` 模块 (`fetch_playerdata/load_playerdata/player_save`)。
- 地图与禁区节奏：`map` 模块（如 `get_area_wavenum/init_areatiming`）。
- 模板渲染：`template.func.php`（模板编译）+ `template()`/`dump_template()` 调用点（集中在页面脚本和模块）。

`command_act.php` 中：
1. `load_playerdata` + `pre_act/act/post_act` 执行业务；
2. `prepare_response_content` 和 `parse_interface_*` 组装前端更新块；
3. `gencode($gamedata)` 返回 JSON。

---

## 6. 常见流程示例

### 示例 A：玩家点击“攻击”
1. 前端 POST 到 `command.php`。
2. 若开启 daemon：client 选 daemon 并转发 socket。
3. daemon 执行 `common.inc.php` → `sys::routine()` → `command_act.php`。
4. `player::act()` 里分发到 battle/item 等模块。
5. 返回编码后的 `gamedata` 给前端。

### 示例 B：玩家打开游戏主界面
1. `game.php` 调 `render_page('command_game')`。
2. `command.php` 分到 `include/pages/command_game.php`。
3. 载入玩家数据、聊天摘要、初始命令面板，输出 `template('game')`。

## 新人最容易踩的坑
- 误以为 `command_game` 和 `command_act` 是同一层：前者偏“初次渲染”，后者偏“动作执行”。
- 调试 `routine` 时只盯一个入口：实际会在多个路径触发。
- 在 daemon 下直接 `var_dump` 可能污染 socket 回包。

## 待确认
- 当前线上是否将 `render_page()` 统一走 `command.php` 渲染，还是存在直出模板的遗留入口（需结合部署环境验证）。
