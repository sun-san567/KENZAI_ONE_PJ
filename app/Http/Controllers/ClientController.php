<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Company;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ClientsImport; // インポートクラスを使います

class ClientController extends Controller
{
    /**
     * クライアント一覧ページ
     */
    public function index()
    {
        $clients = Client::paginate(10); // 1ページあたり10件のデータを取得
        return view('clients.index', compact('clients'));
    }

    /**
     * クライアント登録フォーム
     */
    public function create()
    {
        $companies = Company::all(); // 会社一覧を取得
        return view('clients.create', compact('companies'));
    }

    /**
     * クライアント登録処理
     */
    public function store(Request $request)
    {
        $request->validate([
            'company_id' => 'required|exists:companies,id', // 会社IDが存在することを確認
            'name' => 'required|string|max:255|unique:clients,name', // クライアント名はユニーク
            'phone' => 'nullable|string|max:20', // 電話番号は任意、最大20文字
            'address' => 'nullable|string|max:255', // 住所も任意
        ]);

        Client::create($request->only('company_id', 'name', 'phone', 'address')); // 必要な項目のみ登録

        return redirect()->route('clients.index')->with('success', 'クライアントを登録しました');
    }

    /**
     * クライアント詳細
     */
    public function show(Client $client)
    {
        return view('clients.show', compact('client'));
    }

    /**
     * クライアント編集フォーム
     */
    public function edit(Client $client)
    {
        $companies = Company::all(); // 会社一覧を再度取得
        return view('clients.edit', compact('client', 'companies'));
    }

    /**
     * クライアント更新処理
     */
    public function update(Request $request, Client $client)
    {
        $request->validate([
            'company_id' => 'required|exists:companies,id', // 会社IDが存在することを確認
            'name' => 'required|string|max:255|unique:clients,name,' . $client->id, // 同じ名前が他に無いか確認
            'phone' => 'nullable|string|max:20', // 電話番号は任意、最大20文字
            'address' => 'nullable|string|max:255', // 住所も任意
        ]);

        $client->update($request->only('company_id', 'name', 'phone', 'address')); // クライアント情報を更新

        return redirect()->route('clients.index')->with('success', 'クライアント情報を更新しました');
    }

    /**
     * クライアント削除処理
     */
    public function destroy(Client $client)
    {
        $client->delete(); // クライアント情報を削除
        return redirect()->route('clients.index')->with('success', 'クライアントを削除しました');
    }

    /**
     * クライアントインポート処理
     */
    public function import(Request $request)
    {
        // バリデーション
        $request->validate([
            'csv_file' => 'required|mimes:csv,txt' // CSVまたはテキストファイルのみ
        ]);

        // CSVファイルをインポート
        try {
            Excel::import(new ClientsImport, $request->file('csv_file'));
            return redirect()->route('clients.index')->with('success', 'CSVファイルが正常にインポートされました！');
        } catch (\Exception $e) {
            return back()->with('error', 'インポート中にエラーが発生しました。');
        }
    }

    /**
     * インポート用CSVフォーマットのダウンロード
     */
    public function downloadFormat()
    {
        // CSVフォーマットファイルをダウンロード
        $file = public_path('storage/clients_format.csv');

        // ファイルが存在しない場合の処理
        if (!file_exists($file)) {
            return back()->with('error', 'インポートフォーマットファイルが見つかりません');
        }

        return response()->download($file); // ファイルをダウンロード
    }
}
