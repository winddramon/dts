# 00_START_HERE｜新维护者起步总览

> 一句话摘要：先建立“入口 → 模块链 → 模式扩展点 → 运行时产物”的心智模型，再动代码。

## 适合谁看
- 第一次接触 `Unstable` 分支的 PHP 开发者。
- 需要让 LLM/Agent 快速定位改动点的维护者。

## 建议先读什么
1. `docs/01_RUNTIME_FLOW.md`
2. `docs/02_MODULE_SYSTEM.md`
3. `docs/03_DIRECTORY_MAP.md`

## 关键文件列表（最小必读）
- `command.php`（统一命令入口 + daemon/client 分流）
- `include/common.inc.php`（模块加载 + routine 触发）
- `include/modules/modules.func.php`（模块钩子与 `import_module()`）
- `gamedata/modules.list.php`（模块装配清单）
- `include/valid.func.php`（玩家入场与卡片应用）
- `include/pages/command_act.php`（游戏内动作处理）

## 这个分支最重要的架构认知

### 1) 这不是“单体 include 项目”，而是“模块链式覆写”项目
这里的核心不是“找某个大函数改掉”，而是：
- 一个函数名可被多个模块按依赖顺序覆盖；
- 每层通过 `if (eval(__MAGIC__)) return $___RET_VALUE;` + `$chprocess(...)` 向下游链路传递；
- 模块顺序来自 `gamedata/modules.list.php`，并受依赖关系校验。  

### 2) `command.php` 既可能是 Web 请求处理器，也可能是 daemon server
开启 `$___MOD_SRV` 后，同一个 `command.php` 会出现三种身份：
- 启动 daemon 的“server 模式”；
- 浏览器请求触发的“client 转发模式”；
- daemon 内部 `include` 自己后执行的“真正业务执行模式”。

### 3) “源码目录”和“运行/编译产物目录”必须区分
- 源码主看：`include/modules/**`、`include/pages/**`、`templates/default/**`。
- 运行/产物主看：`gamedata/run`、`gamedata/modinit`、`gamedata/templates`、`gamedata/tmp`。
- 生产环境开启 ADV 后，业务执行可能来自 `gamedata/run`，不是直接跑 `include/modules`。

## 新人最容易踩的坑
1. **改了模块源码但页面没变化**：通常是忘了在 `modulemng.php` 重新编译（ADV/daemon 下必踩）。
2. **把 `gamedata/tmp` 当源码读**：里面大量是运行时锁、socket、缓存、响应中间文件。
3. **在技能/模块函数里漏写 `__MAGIC__` 开头**：会破坏钩子链。
4. **在某模式写死逻辑但没加 `if ($gametype==X)`**：污染其他模式。
5. **把 daemon 问题误判为业务 bug**：先确认是否走了 client->server 转发链。

## 1~2 小时上手路线（建议）
- 第 0~20 分钟：读 `command.php` + `include/common.inc.php`，掌握请求分流。
- 第 20~50 分钟：读 `modules.func.php` + 任意 2~3 个模块 `module.inc.php`/`main.php`。
- 第 50~80 分钟：读 `cardbase` + `valid.func.php`，理解“入场时效应注入”。
- 第 80~120 分钟：读一个 gtype（如 `gtype1`）+ 一个 instance（如 `instance10_rogue`）。

## 待确认 / 需要人工验证
- 当前线上是否默认启用 `$___MOD_CODE_ADV2/$___MOD_SRV`：仓库里仅有 sample，实际配置在被 `.gitignore` 的本地文件中。
- 某些旧注释提到“已废弃/已架空”函数，仍可能被历史页面或脚本调用，需在真实运行日志里确认。
