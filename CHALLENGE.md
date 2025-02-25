24 サマーインターンシップ向けコーディング試験
===============================================================================

**※※※ 担当面接官が事前確認を行うため、面接日前日の 18:00 までに Push をよろしくお願いします ※※※**


課題内容
-------------------------------------------------------------------------------

### 課題 1 - 要件からモデルを考える

あなたは映画を見に行こうとしています。1 人で行くか、複数人で行くかは未定です。直近の上映スケジュールと現在の残り席数は以下の通りです。

**上映スケジュールと残り席数：**

| スケジュール          | 残り席数 |
| --------------------- | -------- |
| 2023/11/30 (木) 15:00 | 4        |
| 2023/11/30 (木) 20:00 | 6        |
| 2023/12/01 (金) 15:00 | 1        |
| 2023/12/01 (金) 20:00 | 2        |
| 2023/12/02 (土) 15:00 | 4        |
| 2023/12/02 (土) 20:00 | 5        |

以下の料金表および注意事項を見て、「`現在日時` と `参加者の構成` を与えると、`最安値で観覧できる日時と料金` を返すシステム」をモデリングしてください。

ただし、`最安値になる日時が複数存在する場合は最も早い日時のみ` を返してください。
また `適当な日時が存在しない場合はその旨がわかる情報` を返してください。

**通常料金：**

| 区分               | 料金   |
| ------------------ | ------ |
| 一般               | ¥1,600 |
| 未成年 (18 歳未満) | ¥1,000 |

**割引料金：**

| 区分                                              | 料金   |
| ------------------------------------------------- | ------ |
| ファーストデイ割 (毎月 1 日)                      | ¥1,000 |
| レイトショー (毎日 20 時以降)                     | ¥1,400 |
| 平日シニア割 (平日 10 時 〜 20 時まで、60 歳以上) | ¥1,200 |

**注意事項：**

- 未成年は 20 時以降入場不可

**この課題の想定成果物：**

- [概念クラス図](https://www.ogis-ri.co.jp/otc/swec/process/am-res/am/artifacts/classDiagram.html)，およびその意図の解説資料
    - ※ 概念クラス図を実装に起こす際に、必ずしもクラス機構を用いる必要はありません
- 各概念クラスに対する、入出力のインターフェース設計，およびその意図の解説資料

### 課題 2 - 考えたモデルをシステムとして実装する

課題 1 で得たモデリング結果を元に、**任意のプログラミング言語** を用いてシステムとして実装してください。

この際、ご自身の得意分野に応じて、以下の **どちらか** を追加で考慮してください：

- フロントエンド: UI も含めてシステムを実装してください
    - ※ Webブラウザで動作するものとします
    - ※ デスクトップアプリとして動作させることは想定していないため、Unity / .NET / Qt などのフレームワークを用いた実装は選考対象外となります
- バックエンド: Web API も含めてシステムを実装してください
    - ※ UI の実装は不要です

**この課題の想定成果物：**

- localhostで動作確認可能なソースコード
    - ※ 実環境へのデプロイは不要です
- 3rd Party のフレームワークやライブラリを追加導入した場合は、その選定意図の解説資料
- (Optional) 検証用テストコードの導入
- (Optional) 型検査など、静的解析を行うための仕組みの導入


課題に取り組むにあたっての補足事項
-------------------------------------------------------------------------------

- 答えが明確にあるタイプの課題ではありません
    - 非機能要件の考え方含めて、候補者ごとに実装結果のバリエーションがでることを期待しています
- すべての課題を完璧にこなす必要はありません
    - 可能な範囲で取り組んでいただき、未着手事項がある場合は面接中に補足してください
- Union Find や Dijkstra 法などの、いわゆるアルゴリズム的に解いてもらうことを目的にしたものではありません
    - アルゴリズムの巧みさだけではなく、ソースコード自体の品質も意識してみてください
