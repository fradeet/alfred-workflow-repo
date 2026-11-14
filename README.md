# Alfred Workflows

集中维护的 Alfred 工作流。每个项目独立管理源码、依赖、版本和安装包。

| 工作流 | 项目目录 | 运行目录 |
| --- | --- | --- |
| Bangumi | [workflows/bangumi](workflows/bangumi/) | `workflows/bangumi/workflow/` |
| Folo2 | [workflows/folo2](workflows/folo2/) | `workflows/folo2/workflow/` |
| Hypothesis | [workflows/hypothesis](workflows/hypothesis/) | `workflows/hypothesis/workflow/` |
| Apple Music | [workflows/apple-music](workflows/apple-music/) | `workflows/apple-music/workflow/` |
| Kikoeru | [workflows/kikoeru](workflows/kikoeru/) | `workflows/kikoeru/workflow/` |
| PHP Docs Search | [workflows/php-docs-search](workflows/php-docs-search/) | `workflows/php-docs-search/workflow/` |
| Save Text to Folder | [workflows/save-text-to-folder](workflows/save-text-to-folder/) | `workflows/save-text-to-folder/workflow/` |
| Zed | [workflows/zed](workflows/zed/) | `workflows/zed/workflow/` |

## 开发与安装

先进入对应项目目录，按项目自身的说明准备依赖，再安装其 `workflow/` 目录或打包后的 `.alfredworkflow` 文件。

PHP 项目的依赖安装位置：

```sh
# 在 bangumi 或 hypothesis 项目目录内
composer install --working-dir=workflow

# 在 apple-music 项目目录内，其 composer.json 已指定 workflow/vendor
composer install
```

Kikoeru 使用 Bashly，在项目目录运行 `./script/sync-cli.sh` 重新生成运行文件。其他工作流的要求见各自文档和源文件。

本次迁移保留原项目当前状态。Folo2 尚缺运行入口，部分 Shell 源码存在原有语法问题；详细来源、检查结果和待处理事项见 [MIGRATION.md](MIGRATION.md)。

## 目录约定

- `workflows/<name>/` 包含一个项目的源码、开发文档、配置和测试。
- `workflows/<name>/workflow/` 包含 Alfred 运行和打包所需的文件。
- 无需编译的源码可直接放在 `workflow/` 内；需要编译的项目可在项目层维护 `src/`，向 `workflow/` 输出运行文件。
- 每个工作流独立维护版本，发布标签可使用 `<name>/v<version>`。
- 不提交依赖安装目录、用户偏好、凭据、缓存和导出的安装包。
- 不包含模板项目。

## 许可证

仓库根目录保留现有 [LICENSE](LICENSE)。各项目原有的 `LICENSE` / `LICENSE.txt` 已随项目保留；第三方代码应遵循其自身授权。没有授权说明的来源需要在公开发布前核实，详见迁移说明。
