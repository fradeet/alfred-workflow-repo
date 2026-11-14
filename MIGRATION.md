# 迁移记录

迁移日期：2026-11-14。采用当前工作目录快照复制，不导入各仓库的 Git 历史。原目录、原 Git 状态和 Alfred 当前安装位置均保持原样。

## 来源

下列路径均相对于 `/Users/fradeet/Code/`：

| 项目 | 来源 |
| --- | --- |
| `workflows/bangumi/` | `alfred-bangumi-workflow/` |
| `workflows/folo2/` | `alfred-workflow/folo-workflow2/` |
| `workflows/hypothesis/` | `alfred-hypothesis-workflow/` |
| `workflows/apple-music/` | `alfred-apple-music-recommand/` |
| `workflows/kikoeru/` | `alfred-kikoeru-workflow/` |
| `workflows/php-docs-search/` | `alfred-php-docs-search/` |
| `workflows/save-text-to-folder/` | `alfred-save-text-to-folder/` |
| `workflows/zed/` | `alfred-zed/` |

不迁移 `alfred-kikoeru`、`alfred-folo`、模板项目和此前待核对的其他项目。

## 导入处理

- 复制 201 个现有文件，保留可执行权限；排除嵌套 `.git`、旧 `.gitmodules`、已安装依赖、用户偏好、环境凭据文件、同步缓存和导出的安装包。
- 保留原项目的开发规范、项目内技能、许可证、源码和 lockfile。
- 将 Kikoeru 的 `alfred_workflow/` 改为 `workflow/`，更新其 README、忽略规则和 `script/sync-cli.sh` 中对应的路径。忽略规则只排除项目根目录的临时 CLI，保留生成的 `workflow/kikoeru-cli`，供工作流运行。
- 将 Folo2 README 中指向仓库根目录 `/workflow/` 的链接改为项目相对链接。
- 保留 PHP Docs 工作目录中已删除 `workflow/GetDocsListAlfred.sh` 的状态；没有恢复已删除的旧脚本。

## 原子模块

两个项目中的 AlfredSFType 均转为普通源码目录，不再依赖子模块初始化。

来源：`https://gist.github.com/008f15019d1bbf99181a96d1ccf3db93`

| 项目 | 导入方式 |
| --- | --- |
| Apple Music | 保留当前子模块工作目录内容，HEAD 为 `f5d8eb2a27daae6918740ed641a186b404326620`；父仓库原锁定提交为 `a17b38ba3276c690f3513f77ebf21be2fa978772` |
| PHP Docs Search | 原子模块未初始化，从本地已有 Git 对象恢复该项目锁定的 `a17b38ba3276c690f3513f77ebf21be2fa978772`，新增两个文件 |

AlfredSFType 快照未包含独立许可证文件；本次保留来源和提交信息，不推定其授权。PHP Docs Search、Save Text to Folder 和 Zed 原目录也没有项目许可证，公开发布前需要核实。

## 检查结果

- 复制文件已逐一核对内容及可执行权限；除上述 4 个路径相关文本文件外，内容与来源一致。
- 8 份 `workflow/info.plist` 通过 `plutil -lint`。
- 111 条 Alfred 节点连接的源和目标 UID 均有效。
- 100 个 PHP 文件通过 `php -l`。
- 23 个 Shell 文件中，19 个通过 `bash -n`；下列 4 个原文件已存在问题，迁移时未改动。

| 文件 | 原有问题 |
| --- | --- |
| `workflows/folo2/src/timeline_command.sh` | CRLF 换行导致 Bash 误报 `if` 未闭合；转换为 LF 后语法检查通过 |
| `workflows/folo2/src/lib/folo/run_folo.sh` | CRLF 换行导致 Bash 语法检查失败 |
| `workflows/folo2/src/lib/verbose/debug.sh` | CRLF 换行导致 Bash 语法检查失败 |
| `workflows/kikoeru/src/lib/verbose/debug.sh` | CRLF 换行导致 Bash 语法检查失败 |

## 待处理事项

- **Folo2 尚不能完整运行**：原 `workflow/info.plist` 引用了以下缺失文件，来源目录也没有对应的构建清单或 TypeScript 源码。本次按要求没有从旧 `alfred-folo` 补入代码。
  - `dist/app/last-timeline-query.js`
  - `dist/app/login.js`
  - `dist/app/mark-all-read.js`
  - `dist/app/mark-read.js`
  - `dist/app/subscriptions.js`
  - `dist/app/unread.js`
- 修复上述 Shell 源码问题后，再验证对应的 Bashly 构建。
- Hypothesis 的 README 和 Composer 项目信息仍包含模板说明，需要按真实功能整理。
- Kikoeru README 原本引用的 `script/sync_readme.sh` 不存在，需要补充工具或修正文档。
- PHP 依赖目录未复制，需要按根 README 的说明安装依赖后再运行各项目的完整检查。
- 本次只完成源文件导入和静态检查，未执行访问外部 API 的功能测试、账户登录、安装包发布或已安装工作流切换。
