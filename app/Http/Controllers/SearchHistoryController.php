<?php
// filepath: c:\xampp\htdocs\bago\Project\app\Http\Controllers\SearchHistoryController.php
namespace App\Http\Controllers;

use App\Models\SearchHistory;
use Illuminate\Http\Request;

class SearchHistoryController extends Controller
{
    public function destroy($id)
    {
        $searchHistory = SearchHistory::findOrFail($id);
        $searchHistory->delete();
    
        return response()->json(['success' => 'Search history deleted successfully.']);
    }
}