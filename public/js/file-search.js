// ファイル検索モジュール
window.fileSearchModule = (function () {
    // デバッグモード設定
    const DEBUG_MODE = false; // 本番環境ではfalseに設定
    
    // デバッグ関数の定義を修正
    function debug(message, data) {
        // 常にコンソールには出力（開発時の確認用）
        console.log(`[${new Date().toLocaleTimeString()}] ${message}`, data || '');
        
        // デバッグモードがオフなら早期リターン
        if (!DEBUG_MODE) return;
        
        // DOM要素が存在する場合のみ処理（エラー防止）
        const debugContent = document.getElementById('debugContent');
        if (debugContent) {
            const timestamp = new Date().toLocaleTimeString();
            let logMessage = `<div class="py-1">[${timestamp}] ${message}</div>`;
            
            if (data) {
                logMessage += `<pre class="bg-gray-200 p-1 rounded">${JSON.stringify(data, null, 2)}</pre>`;
            }
            
            logMessage += `<div class="border-b border-gray-300 my-1"></div>`;
            
            debugContent.innerHTML += logMessage;
            debugContent.scrollTop = debugContent.scrollHeight;
        }
    }

    // 検索を実行する関数
    function performSearch() {
        debug('検索実行開始');
        
        try {
            // 必要なDOM要素の取得
            const searchInput = document.getElementById('searchInput');
            const typeFilter = document.getElementById('typeFilter');
            const fileListBody = document.getElementById('fileListBody');
            
            // 要素が見つからない場合は早期リターン（エラー防止）
            if (!searchInput || !typeFilter || !fileListBody) {
                console.error('検索に必要なDOM要素が見つかりません');
                return;
            }
            
            // 検索パラメータの取得
            const searchValue = searchInput.value.trim();
            const typeValue = typeFilter.value;
            
            debug('検索パラメータ', { 
                search: searchValue,
                type: typeValue
            });
            
            // プロジェクトIDの取得
            const projectId = getProjectIdFromUrl();
            
            if (!projectId) {
                console.error('プロジェクトIDを取得できませんでした');
                return;
            }
            
            // ローディング表示
            fileListBody.innerHTML = `
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center">
                        <div class="flex justify-center">
                            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500"></div>
                        </div>
                        <p class="mt-2 text-gray-500">検索中...</p>
                    </td>
                </tr>
            `;
            
            // CSRF対策
            const csrfToken =
                document
                    .querySelector('meta[name="csrf-token"]')
                    ?.getAttribute("content") || "";

            // URL構築
            const url = `/projects/${projectId}/files?search=${encodeURIComponent(
                searchValue
            )}&type=${encodeURIComponent(typeValue)}&ajax=1`;

            debug("リクエストURL", url);

            // Fetch API
            fetch(url, {
                method: "GET",
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                    Accept: "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                },
            })
                .then((response) => {
                    debug("レスポンス受信", {
                        status: response.status,
                        ok: response.ok,
                        contentType: response.headers.get("Content-Type"),
                    });

                    if (!response.ok) {
                        throw new Error(`サーバーエラー: ${response.status}`);
                    }
                    return response.text();
                })
                .then((responseText) => {
                    debug("レスポンス内容", {
                        length: responseText.length,
                        preview: responseText.substring(0, 100) + "...",
                    });

                    try {
                        // JSONかどうか確認
                        const data = JSON.parse(responseText);
                        debug("JSONとしてパース成功", {
                            hasHtml: !!data.html,
                            contentLength: data.html ? data.html.length : 0,
                        });

                        // 結果を表示
                        if (data.html) {
                            fileListBody.innerHTML = data.html;
                            debug("検索結果の表示完了");
                        } else {
                            debug("検索結果のHTMLがレスポンスに含まれていません");
                            fileListBody.innerHTML =
                                '<tr><td colspan="5" class="px-6 py-4 text-center">検索結果を表示できません</td></tr>';
                        }
                    } catch (e) {
                        // HTMLとして処理
                        debug("JSONパースエラー - HTMLとして処理", {
                            error: e.message,
                        });

                        if (
                            responseText.includes("<tr") &&
                            responseText.includes("</tr>")
                        ) {
                            fileListBody.innerHTML = responseText;
                            debug("HTMLとして処理完了");
                        } else {
                            debug("有効なHTML/JSONではありません", {
                                isHtml: responseText.includes("<html"),
                                firstChars: responseText.substring(0, 20),
                            });
                            fileListBody.innerHTML =
                                '<tr><td colspan="5" class="px-6 py-4 text-center text-red-500">無効なレスポンス形式</td></tr>';
                        }
                    }
                })
                .catch((error) => {
                    debug("エラー発生", {
                        message: error.message,
                        stack: error.stack,
                    });

                    // サーバーエラーの場合、レスポンスボディを解析して詳細を表示
                    if (error.message.includes("500")) {
                        debug("サーバー側エラー - レスポンス本文を取得試行");

                        // エラーレスポンスのボディを取得
                        fetch(url, {
                            method: "GET",
                            headers: {
                                "X-Requested-With": "XMLHttpRequest",
                                Accept: "application/json",
                                "X-CSRF-TOKEN": csrfToken,
                            },
                        })
                            .then((response) => response.text())
                            .then((errorText) => {
                                try {
                                    const errorData = JSON.parse(errorText);
                                    debug("サーバーエラー詳細", errorData);

                                    fileListBody.innerHTML = `
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-red-500">
                                            <div class="mb-2"><i class="fas fa-exclamation-triangle text-xl"></i></div>
                                            <p>${
                                                errorData.error ||
                                                "サーバーエラーが発生しました"
                                            }</p>
                                            ${
                                                errorData.message
                                                    ? `<p class="text-xs mt-2">${errorData.message}</p>`
                                                    : ""
                                            }
                                        </td>
                                    </tr>
                                `;
                                } catch (e) {
                                    debug("エラーレスポンスのパースに失敗", {
                                        error: e.message,
                                    });
                                    fileListBody.innerHTML = `
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-red-500">
                                            <div class="mb-2"><i class="fas fa-exclamation-triangle text-xl"></i></div>
                                            <p>サーバーエラー: 詳細を取得できませんでした</p>
                                        </td>
                                    </tr>
                                `;
                                }
                            })
                            .catch(() => {
                                fileListBody.innerHTML = `
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-red-500">
                                        <div class="mb-2"><i class="fas fa-exclamation-triangle text-xl"></i></div>
                                        <p>${error.message}</p>
                                    </td>
                                </tr>
                            `;
                            });
                    } else {
                        fileListBody.innerHTML = `
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-red-500">
                                    <div class="mb-2"><i class="fas fa-exclamation-triangle text-xl"></i></div>
                                    <p>${error.message}</p>
                                </td>
                            </tr>
                        `;
                    }
                });
        } catch (e) {
            // 全体的なエラーハンドリング
            console.error('検索実行中に予期せぬエラーが発生しました', e);
            
            const fileListBody = document.getElementById('fileListBody');
            if (fileListBody) {
                fileListBody.innerHTML = `
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-red-500">
                            <div class="mb-2"><i class="fas fa-exclamation-triangle text-xl"></i></div>
                            <p>検索処理中にエラーが発生しました</p>
                        </td>
                    </tr>
                `;
            }
        }
    }

    // URLからプロジェクトIDを取得する関数
    function getProjectIdFromUrl() {
        // /projects/{projectId}/files のパターンを想定
        const pathParts = window.location.pathname.split('/');
        const projectsIndex = pathParts.indexOf('projects');
        
        if (projectsIndex !== -1 && projectsIndex + 1 < pathParts.length) {
            return pathParts[projectsIndex + 1];
        }
        
        return null;
    }

    // ページ読み込み完了時の処理
    function init() {
        debug('ファイル検索モジュール初期化');
        
        try {
            // DOM要素の存在確認
            const elements = {
                searchButton: document.getElementById('searchButton'),
                searchInput: document.getElementById('searchInput'),
                typeFilter: document.getElementById('typeFilter'),
                fileListBody: document.getElementById('fileListBody')
            };
            
            debug('DOM要素チェック', {
                searchButton: !!elements.searchButton,
                searchInput: !!elements.searchInput,
                typeFilter: !!elements.typeFilter,
                fileListBody: !!elements.fileListBody
            });
            
            // 要素が存在する場合のみイベントリスナーを設定
            if (elements.searchInput) {
                elements.searchInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        debug('Enterキーが押されました');
                        performSearch();
                    }
                });
                debug('検索入力欄にEnterキーイベントを設定完了');
            }
        } catch (e) {
            console.error('初期化中にエラーが発生しました', e);
        }
    }

    // 公開API
    return {
        performSearch: performSearch,
        init: init,
        debug: debug
    };
})();

// ページ読み込み完了時に初期化
document.addEventListener('DOMContentLoaded', function() {
    try {
        window.fileSearchModule.init();
        console.log('ファイル検索モジュールの初期化が完了しました');
    } catch (e) {
        console.error('モジュール初期化エラー:', e);
    }
});
