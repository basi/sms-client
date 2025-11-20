# GitHub Actions セットアップガイド

## 概要

このプロジェクトでは、バージョン管理とリリースの自動化のために GitHub Actions を使用しています。
適切に動作させるためには、GitHub Personal Access Token (GH_PAT) の設定が必要です。

## なぜ GH_PAT が必要か？

デフォルトの `GITHUB_TOKEN` には以下の制限があります：
- `GITHUB_TOKEN` で作成された PR やコミットは、他の GitHub Actions ワークフローをトリガーしません
- これは無限ループを防ぐためのセキュリティ機能です

`GH_PAT` を使用することで：
- PR 作成後に自動的に他のワークフローがトリガーされます
- バージョンバンプ PR のマージ後に自動的にリリースが作成されます

## セットアップ手順

### 1. Personal Access Token の作成

1. GitHub にログインし、右上のプロフィールアイコンをクリック
2. **Settings** → **Developer settings** → **Personal access tokens** → **Tokens (classic)** へ移動
3. **Generate new token** → **Generate new token (classic)** をクリック
4. 以下の設定を行う：
   - **Note**: `SMS Client GitHub Actions` など分かりやすい名前
   - **Expiration**: お好みの有効期限（推奨: 90日以上）
   - **Scopes**: 以下を選択
     - `repo` (フルアクセス)
     - `workflow` (ワークフローの更新権限)

5. **Generate token** をクリックし、表示されたトークンをコピー（この画面を離れると二度と表示されません）

### 2. リポジトリへの設定

1. リポジトリのページで **Settings** タブをクリック
2. 左側メニューの **Secrets and variables** → **Actions** をクリック
3. **New repository secret** をクリック
4. 以下を入力：
   - **Name**: `GH_PAT`
   - **Secret**: 先ほどコピーした Personal Access Token
5. **Add secret** をクリック

## ワークフローの説明

### create-version-bump-pr.yml
- **トリガー**: main ブランチへの PR マージ時
- **動作**: バージョンを自動インクリメントする PR を作成
- **使用する Secret**: `GH_PAT`

### bump-version.yml
- **トリガー**: バージョンバンプ PR のマージ時
- **動作**: タグとリリースを自動作成
- **使用する Secret**: `GH_PAT`

## トラブルシューティング

### PR が作成されない場合
1. `GH_PAT` が正しく設定されているか確認
2. Personal Access Token の有効期限が切れていないか確認
3. Token に必要な権限（`repo`, `workflow`）があるか確認

### ワークフローがトリガーされない場合
1. GitHub Actions が有効になっているか確認（Settings → Actions → General）
2. ワークフローファイルが正しい場所（`.github/workflows/`）にあるか確認

### エラーログの確認方法
1. リポジトリの **Actions** タブをクリック
2. 失敗したワークフローをクリック
3. ジョブ名をクリックしてログを確認

## セキュリティに関する注意事項

- Personal Access Token は機密情報です。絶対にコード内にハードコーディングしないでください
- Token の権限は必要最小限に設定してください
- 定期的に Token を更新することを推奨します
- 不要になった Token は速やかに削除してください

## 参考リンク

- [GitHub Personal Access Tokens ドキュメント](https://docs.github.com/en/authentication/keeping-your-account-and-data-secure/creating-a-personal-access-token)
- [GitHub Actions Secrets ドキュメント](https://docs.github.com/en/actions/security-guides/encrypted-secrets)
- [GitHub Actions ワークフロー構文](https://docs.github.com/en/actions/using-workflows/workflow-syntax-for-github-actions)