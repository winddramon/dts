# 10_MAINTAINER_CHEATSHEET｜维护者速查表

> 一句话摘要：改什么就先看哪 5 个位置。

## 适合谁看
- 已经熟悉项目，想快速开工的人。

## 建议先读什么
- `docs/00_START_HERE.md`

## 关键文件列表
- `command.php`
- `include/common.inc.php`
- `include/valid.func.php`
- `include/modules/extra/card/cardbase/*`
- `include/modules/extra/instance/*`

## 新卡片：先看这 5 个位置
1. `include/modules/extra/card/cardbase/config/card.config.php`
2. `include/modules/extra/card/cardbase/main.php`（`enter_battlefield_cardproc`）
3. `include/modules/extra/card/cardbase/main.php`（`card_validate*`）
4. `include/pages/command_valid.php`
5. `include/valid.func.php`

## 新技能：先看这 5 个位置
1. 目标目录的 `skillXXX/module.inc.php`
2. 目标目录的 `skillXXX/main.php`
3. `include/modules/base/skillbase/*`
4. 挂接点（`cardbase`/`clubbase`/instance）
5. `gamedata/modules.list.php`

## 新模式：先看这 5 个位置
1. `include/modules/extra/instance/gtype1/main/main.php`（参考）
2. `include/modules/extra/instance/instance10_rogue/main.php`（复杂参考）
3. `include/modules/core/sys/config/gamemode.config.php`
4. 你的新模块 `module.inc.php + main.php`
5. `gamedata/modules.list.php`

## 修 BUG：先看这 5 个位置
1. `command.php`
2. `include/common.inc.php`
3. `include/pages/command_act.php`（或对应页面脚本）
4. 对应业务模块 `main.php`
5. `gamedata/tmp/server`（daemon 状态）

## 具体示例
- “新增卡片失败”排查：先确认 `card.config.php` 写入正确，再看 `card_validate` 是否被当前模式禁用，最后看对应技能模块是否启用。

## 新人最容易踩的坑
- 看错目录层（把运行时产物当源码）。
- 忘记重编译模块缓存。

## 待确认
- 若线上使用自定义部署脚本，以上路径可能有镜像副本，需运维确认。
