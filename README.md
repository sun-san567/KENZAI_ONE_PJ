READMEのファイル修正
## ✅ 実装済み機能（MVP 段階）

-   **ログイン・ログアウト機能（Laravel Breeze）**
-   **会社・部門・ユーザー・クライアントの登録**
-   **プロジェクト管理機能**
    -   クライアント・フェーズ・ユーザー・部門との紐付け
    -   案件名・説明・売上・粗利・見積期限・着工日・竣工日の入力
-   **データモデル & リレーション構築済み**
    -   `projects`, `clients`, `depar# KENZAI-ONE（建材業向けプロジェクト管理システム）

建設業・建材業に特化したプロジェクト管理 Web アプリケーションです。  
営業・積算・工事・などのプロセスの進捗管理を一元化し、**クライアント別のプロジェクト可視化**と**部門・担当者単位の進捗共有**を目指しています。

⚠️ 本アプリは現在開発中です（MVP 段階）。一部機能は未実装です。

---

## 🔧 実装予定機能（開発計画中）

### 🎯 UI・管理機能

-   フェーズ進行ボード UI（ドラッグ＆ドロップ対応）
-   プロジェクト一覧画面（検索・絞り込み対応）
-   ファイルアップロード（PDF・Excel・CAD 等）
-       post_max_size => 8M => 8M
-       upload_max_filesize => 5M => 5M
-   ファイルのタグ管理（見積書・図面・契約書など）
-   ファイル共有リンク発行（外部共有想定）
-   工程表（ガントチャート形式を予定）
-   仕入先管理（会社・取引条件などを一元管理）

### 🤝 協力・連携機能

-   職人招待（メール招待またはリンク発行）
-   アカウント発行・閲覧権限管理（将来的なアクセス制御）

### 🧠 AI 活用（研究・検討段階）

-   図面や仕様書からの**材料数量自動拾い出し**（画像解析・自然言語処理）

---

## 🛠️ 技術スタック

| 項目           | 使用技術                                      |
| -------------- | --------------------------------------------- |
| フロントエンド | HTML / CSS / JavaScript（※View は一部未実装） |
| バックエンド   | Laravel 10.x                                  |
| 認証           | Laravel Breeze                                |
| DB             | MySQL / MariaDB                               |
| コンテナ環境   | Docker（ローカル開発用）                      |
| バージョン管理 | GitHub                                        |
| インフラ       | さくらインターネット（レンタルサーバ）        |

---tments`, `users`, `phases`, `categories`

-   **テストデータの Seeder 整備**
-   会社 / 部門 / ユーザー の初期データ生成（`TestOrgStructureSeeder` 使用）
✅ 現状の KENZAI-ONE が解決している顧客の課題
    ① プロジェクト情報がバラバラで属人化している
    課題：案件ごとに Excel や紙で管理、担当者しか把握できない
    会社・部門・担当者・フェーズ（進捗）などの情報が連携されていない
    解決：顧客・部門・担当者に紐づけて案件を一元管理
    進捗や担当の割り当てが明確になり、社内共有がしやすい

    ② 案件ステータスが見えにくく、漏れ・遅れが発生している
    課題：「どの案件が今どの段階にあるか」がパッと見てわからない
    対応漏れ・遅延が後手に回り、営業機会を失っている
    解決：「フェーズ（進捗ステータス）」管理により、各案件の状況を分類・可視化
    今後はドラッグ&ドロップで進捗操作できる直感的 UI を提供予定
    
    ③ 案件数が増えると顧客・部門・担当者ごとの把握が難しくなる
    課題：「A 部門の案件」「B さんが担当している案件」などがすぐに抽出できない
    誰が何を担当しているのか、管理が煩雑になる
    解決：company_id・department_id・user_id で案件を構造化
    検索・フィルタ機能（今後実装）でスムーズに抽出できるよう設計中
    
    ④ 見積期限や着工日・完工日を忘れてしまう
    課題：日付管理がカレンダーや手帳頼りで、データ化されていない
    案件の優先順位づけやリソース配分が直感でしかできない
    解決：各案件に estimate_deadline, start_date, end_date を設定可能
    今後、工程表機能やリマインド機能で更なる強化予定

    ⑤ システムが複雑すぎて現場が使えない
    課題：従来の SaaS は高機能だが操作が難しく、現場が使いこなせない
    解決：UI/UX は「モーダル完結型」で操作をシンプルに
    PC 操作に特化した構成で、現場管理者・営業担当者も使いやすい
