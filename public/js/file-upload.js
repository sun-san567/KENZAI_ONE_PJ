/**
 * ファイルアップロード機能のモジュール
 *
 * 機能：
 * - ファイル選択とドラッグ＆ドロップの処理
 * - 選択中ファイルの表示と管理
 * - アップロードプロセスと進捗表示
 */
window.FileUploader = (function () {
    // 内部変数
    let currentFiles = new Set();
    let elements = {};

    // 初期化関数
    function init() {
        console.log("FileUploader モジュール初期化");

        // 要素の取得
        elements = {
            form: document.getElementById("uploadForm"),
            fileInput: document.getElementById("fileInput"),
            dropZone: document.getElementById("dropZone"),
            uploadPrompt: document.getElementById("uploadPrompt"),
            selectedFiles: document.getElementById("selectedFiles"),
            fileList: document.getElementById("fileList"),
            fileCount: document.getElementById("fileCount"),
            totalSize: document.getElementById("totalSize"),
            addMoreFiles: document.getElementById("addMoreFiles"),
            statusArea: document.getElementById("statusArea"),
            uploadBtn: document.getElementById("uploadBtn"),
            uploadStatus: document.getElementById("uploadStatus"),
            progressBar: document.getElementById("progressBar"),
            uploadProgress: document.getElementById("uploadProgress"),
        };

        // 要素の存在確認
        if (!elements.fileInput || !elements.dropZone) {
            console.error("必要な要素が見つかりません");
            return;
        }

        // イベントリスナーの設定
        setupEventListeners();
    }

    // イベントリスナーのセットアップ
    function setupEventListeners() {
        // ファイル選択イベント
        elements.fileInput.addEventListener("change", handleFileSelection);

        // 「ファイルを追加」ボタン
        if (elements.addMoreFiles) {
            elements.addMoreFiles.addEventListener("click", () => {
                elements.fileInput.click();
            });
        }

        // ドラッグ＆ドロップイベント
        elements.dropZone.addEventListener("dragover", (e) => {
            e.preventDefault();
            elements.dropZone.classList.add("border-blue-500", "bg-blue-50");
        });

        elements.dropZone.addEventListener("dragleave", (e) => {
            e.preventDefault();
            elements.dropZone.classList.remove("border-blue-500", "bg-blue-50");
        });

        elements.dropZone.addEventListener("drop", (e) => {
            e.preventDefault();
            elements.dropZone.classList.remove("border-blue-500", "bg-blue-50");
            handleFileSelection({
                target: {
                    files: e.dataTransfer.files,
                },
            });
        });

        // フォーム送信イベント
        if (elements.form) {
            elements.form.addEventListener("submit", handleFormSubmit);
        }
    }

    // ファイル選択の処理
    function handleFileSelection(e) {
        const files = Array.from(e.target.files || []);
        if (files.length === 0) return;

        if (elements.uploadStatus) {
            elements.uploadStatus.classList.remove("hidden");
            elements.uploadStatus.innerHTML = `<i class="fas fa-spinner fa-spin mr-2 text-blue-500"></i>アップロード準備中...`;

            setTimeout(() => {
                elements.uploadStatus.innerHTML = `<i class="fas fa-check-circle text-green-500 mr-2"></i>アップロード準備完了 ✅`;
            }, 1000);
        }

        if (elements.uploadPrompt && elements.selectedFiles) {
            elements.uploadPrompt.classList.add("hidden");
            elements.selectedFiles.classList.remove("hidden");
        }

        files.forEach((file) => {
            if (!currentFiles.has(file.name)) {
                currentFiles.add(file.name);
                addFileToList(file);
            }
        });

        updateFileCount();
        updateTotalSize();
    }

    // ファイルリストに追加
    function addFileToList(file) {
        if (!elements.fileList) return;

        const fileItem = document.createElement("div");
        fileItem.className =
            "flex items-center justify-between bg-white p-3 rounded-lg border";

        const fileIcon = getFileIcon(file.type);

        fileItem.innerHTML = `
            <div class="flex items-center space-x-3">
                <i class="fas ${fileIcon.icon} ${fileIcon.color}"></i>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 truncate">${
                        file.name
                    }</p>
                    <p class="text-xs text-gray-500">${formatFileSize(
                        file.size
                    )}</p>
                </div>
            </div>
            <button type="button" 
                    class="text-gray-400 hover:text-red-500"
                    onclick="window.FileUploader.removeFile('${file.name}')">
                <i class="fas fa-times"></i>
            </button>
        `;

        elements.fileList.appendChild(fileItem);
    }

    // ファイル削除
    function removeFile(fileName) {
        currentFiles.delete(fileName);

        if (elements.fileList) {
            const items = elements.fileList.children;
            for (let item of items) {
                if (item.querySelector("p").textContent === fileName) {
                    item.remove();
                    break;
                }
            }
        }

        updateFileCount();
        updateTotalSize();

        if (
            currentFiles.size === 0 &&
            elements.selectedFiles &&
            elements.uploadPrompt
        ) {
            elements.selectedFiles.classList.add("hidden");
            elements.uploadPrompt.classList.remove("hidden");
            elements.fileInput.value = "";
        }
    }

    // ファイル数の更新
    function updateFileCount() {
        if (elements.fileCount) {
            elements.fileCount.textContent = currentFiles.size;
        }
    }

    // 合計サイズの更新
    function updateTotalSize() {
        if (!elements.totalSize || !elements.fileInput) return;

        let total = 0;
        const files = elements.fileInput.files;
        Array.from(files).forEach((file) => {
            if (currentFiles.has(file.name)) {
                total += file.size;
            }
        });
        elements.totalSize.textContent = `合計: ${formatFileSize(total)}`;
    }

    // ファイルタイプに応じたアイコンを取得
    function getFileIcon(mimeType) {
        let icon = "fa-file";
        let color = "text-blue-500";

        if (mimeType.includes("pdf")) {
            icon = "fa-file-pdf";
            color = "text-red-500";
        } else if (mimeType.includes("image")) {
            icon = "fa-file-image";
            color = "text-green-500";
        } else if (mimeType.includes("word")) {
            icon = "fa-file-word";
            color = "text-blue-700";
        } else if (mimeType.includes("excel")) {
            icon = "fa-file-excel";
            color = "text-green-700";
        }

        return { icon, color };
    }

    // ファイルサイズのフォーマット
    function formatFileSize(bytes) {
        if (bytes === 0) return "0 Bytes";
        const k = 1024;
        const sizes = ["Bytes", "KB", "MB", "GB"];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + " " + sizes[i];
    }

    // ファイルアップロード処理関数
    async function submitForm(e) {
        if (e) e.preventDefault();

        if (currentFiles.size === 0) {
            showStatus("ファイルを選択してください", "error");
            return;
        }

        // アップロード中の表示
        if (
            elements.uploadStatus &&
            elements.uploadProgress &&
            elements.progressBar
        ) {
            elements.uploadStatus.innerHTML = `<i class="fas fa-spinner fa-spin mr-2 text-blue-500"></i>アップロード中...`;
            elements.uploadProgress.classList.remove("hidden");
            elements.progressBar.style.width = "0%";

            // プログレスバーシミュレーション
            let progress = 0;
            const interval = setInterval(() => {
                progress += 10;
                elements.progressBar.style.width = progress + "%";

                if (progress >= 100) {
                    clearInterval(interval);
                    elements.uploadStatus.innerHTML = `<i class="fas fa-check-circle text-green-500 mr-2"></i>アップロード完了 ✅`;
                }
            }, 300);
        }

        const formData = new FormData(elements.form);

        try {
            debug("フォーム送信開始");
            const response = await fetch(elements.form.action, {
                method: "POST",
                body: formData,
                headers: {
                    "X-CSRF-TOKEN": document.querySelector(
                        'meta[name="csrf-token"]'
                    ).content,
                    "X-Requested-With": "XMLHttpRequest", // Ajaxリクエストであることを明示
                },
            });

            debug("レスポンス受信:", {
                status: response.status,
                ok: response.ok,
            });

            // レスポンスの種類をチェック
            const contentType = response.headers.get("content-type");
            let result;

            if (contentType && contentType.includes("application/json")) {
                // JSONレスポンスの場合
                result = await response.json();
                debug("JSONレスポンス:", result);
            } else {
                // HTML等のJSONでないレスポンスの場合
                const text = await response.text();
                debug("非JSONレスポンス:", {
                    contentType,
                    textLength: text.length,
                });

                // レスポンスがOKならば成功として扱う
                if (response.ok) {
                    result = {
                        success: true,
                        message: "ファイルがアップロードされました",
                        redirect: window.location.href, // 現在のURLを再利用
                    };
                } else {
                    throw new Error("サーバーからの応答が不正です");
                }
            }

            if (result.success) {
                // アップロード成功時の処理
                debug("アップロード成功:", result.message);
                showStatus(
                    result.message || "ファイルがアップロードされました",
                    "success"
                );

                // ファイル選択をクリア
                clearFileSelection();

                setTimeout(() => {
                    debug("リダイレクト実行:", result.redirect);
                    window.location.href =
                        result.redirect || window.location.href;
                }, 1500);
            } else {
                debug("アップロード失敗:", result.message);
                throw new Error(result.message || "アップロードに失敗しました");
            }
        } catch (error) {
            debug("エラー発生:", error.message);
            showStatus(error.message, "error");
        }
    }

    // ファイル選択をクリアする関数
    function clearFileSelection() {
        // 選択中のファイルをクリア
        currentFiles.clear();

        // ファイル入力欄をリセット
        if (elements.fileInput) {
            elements.fileInput.value = "";
        }

        // ファイルリスト表示をクリア
        if (elements.fileList) {
            elements.fileList.innerHTML = "";
        }

        // ファイル数と合計サイズの表示をリセット
        if (elements.fileCount) {
            elements.fileCount.textContent = "0";
        }

        if (elements.totalSize) {
            elements.totalSize.textContent = "";
        }

        // 選択中ファイル表示を非表示にして、アップロードプロンプトを表示
        if (elements.selectedFiles && elements.uploadPrompt) {
            elements.selectedFiles.classList.add("hidden");
            elements.uploadPrompt.classList.remove("hidden");
        }

        debug("ファイル選択をクリアしました");
    }

    // ステータスメッセージの表示
    function showStatus(message, type) {
        if (!elements.statusArea) return;

        elements.statusArea.textContent = message;
        elements.statusArea.className = "mt-3 p-3 rounded-lg text-center";

        if (type === "success") {
            elements.statusArea.classList.add("bg-green-50", "text-green-700");
        } else if (type === "error") {
            elements.statusArea.classList.add("bg-red-50", "text-red-700");
        }

        elements.statusArea.classList.remove("hidden");
    }

    // 初期化と公開メソッド
    return {
        init: init,
        removeFile: removeFile,
        submitForm: submitForm,
        clearFileSelection: clearFileSelection,
    };
})();

// ページ読み込み完了時に初期化
document.addEventListener("DOMContentLoaded", function () {
    window.FileUploader.init();
});
