# 02_MODULE_SYSTEM｜模块系统本体（最重要）

> 一句话摘要：本仓库通过 `modules.list + module.inc + __MAGIC__ + $chprocess` 构建可叠加覆写的模块链。

## 适合谁看
- 要新增/修改技能、模式、机制的人。

## 建议先读什么
1. `include/modules/modules.func.php`
2. `include/modules/modules.init.template.php`
3. `include/modulemng/modulemng.func.php`
4. 任意模块的 `module.inc.php` 与 `main.php`

## 关键文件列表
- `gamedata/modules.list.php`
- `include/modules/modules.func.php`
- `include/modules/modules.init.template.php`
- `include/modulemng/modulemng.func.php`
- `include/modulemng/modulemng.config.sample.php`

---

## 1) `gamedata/modules.list.php` 是什么
每行格式：
`模块名, 模块路径, 是否启用(0/1)`

例如：
- `sys,core/sys/,1`
- `cardbase,extra/card/cardbase/,1`
- `instance10,extra/instance/instance10_rogue/,1`

它决定：
1. 哪些模块会被加载；
2. 加载顺序（在依赖合法前提下非常关键）；
3. 运行时 `MOD_XXX` 常量是否存在。

---

## 2) 标准模块结构
标准模块目录通常包含：
- `module.inc.php`：模块头信息（依赖、代码清单、模板清单）；
- `main.php`：主体逻辑（钩子函数实现）；
- `config/*.php`：配置数据；
- `*.htm`：模块模板片段。

### `module.inc.php` 头字段含义
- `dependency`：硬依赖，必须存在。
- `dependency_optional`：软依赖，存在则接链，不存在也可运行。
- `conflict`：冲突模块。
- `codelist`：本模块要加载的 php 文件列表。
- `templatelist`：本模块模板基名列表（无需 `.htm` 后缀）。

---

## 3) `__INIT_MODULE__` 做了什么
`module.inc.php` 末尾统一 `require __INIT_MODULE__(__NAMESPACE__,__DIR__)`。

它会：
- 生成/选择模块 init 文件（普通或 adv）；
- 载入 `codelist` 文件；
- 扫描并注册同名函数钩子链（`hook_register`）；
- 生成 `IMPORT_MODULE_XXX_GLOBALS` 常量，供 `import_module()` 注入变量引用。

---

## 4) 命名空间约定与函数链
- 模块名与 namespace 一致（如 `namespace skill71`）。
- 可被覆写函数名在多模块中同名（如 `checkcombo`、`get_shopconfig`）。
- 每个可覆写函数开头都要写：
  `if (eval(__MAGIC__)) return $___RET_VALUE;`

`__MAGIC__` 负责决定当前应调用的“下游函数” `$chprocess`，形成链式执行。

---

## 5) `import_module()` 的作用与风险

### 作用
`eval(import_module('sys','player'))` 会把模块内全局变量引用导入当前函数作用域，避免手写全局映射。

### 风险
1. 大量 `eval(import_module())` 在循环内会有性能负担（可改 `get_var_in_module()`）。
2. daemon 下 `input` 模块有特殊逻辑，变量来自 `___LOCAL_INPUT__VARS__INPUT_VAR_LIST`，误用容易读到旧值。
3. 在模块 `init()` 阶段导入 `sys/input` 有限制（modulemng 激活会检查）。

---

## 6) 钩子式/叠加式架构体现在哪

### 典型模式
- 基础模块给出默认实现（如 `cardbase::card_validate_get_forbidden_cards`）。
- 模式模块按 `gametype` 条件覆写（如 `gtype1`、`instance10` 改卡片可用性）。
- 调用 `$chprocess(...)` 继续传递，最终可形成“基础逻辑 + 模式补丁 + 活动补丁”。

### 例子
- `checkcombo()`：`instance0`、`gtype1`、`instance10` 都会按模式改连斗判定。
- `get_shopconfig()`：多个 gtype/instance 按模式切换配置文件。
- `card_validate_display()`：`cardbase` 默认 + `gtype1/instance10` 强制特定卡。

---

## 7) core / base / extra 分工
- `core`：框架底座（输入、系统状态、玩家/地图主结构、主流程）。
- `base`：常规玩法能力（战斗、道具、武器、探索、称号基础等）。
- `extra`：扩展玩法（卡片、活动、实例模式、附加机制）。

> 推荐思路：先在 `base` 找“公共能力”，再在 `extra` 找“模式化变体”。

---

## 8) 新人读模块的顺序建议
1. 先读 `module.inc.php`（知道它依赖谁、改谁）。
2. 再读 `main.php` 顶部 `init()`（知道注册了哪些常量/描述）。
3. 再读关键被覆写函数。
4. 最后看 `config` 与模板。

## 新人最容易踩的坑
- 只在一个模块里搜函数定义，没意识到同名函数链。
- 新增函数忘写 `__MAGIC__`，导致断链。
- 误把 `optional dependency` 当成必有模块，线上可能不存在。

## 待确认
- 当前线上是否启用 `CODE_ADV2/CODE_COMBINE`（会影响调试体验与报错栈）。
