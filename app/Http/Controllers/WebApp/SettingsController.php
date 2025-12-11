<?php

namespace App\Http\Controllers\WebApp;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\Gender;
use App\Models\Language;
use App\Models\Religion;
use App\Models\MaritalStatus;
use App\Models\Option;

class SettingsController extends Controller
{
    /**
     * Map category names to model classes
     */
    protected $categoryMap = [
        'countries' => Country::class,
        'genders' => Gender::class,
        'languages' => Language::class,
        'religions' => Religion::class,
        'marital-statuses' => MaritalStatus::class,
        'options' => Option::class, // Positions
    ];

    /**
     * Display all dynamic fill categories
     */
    public function index()
    {
        $categories = [
            'countries' => ['name' => 'Countries', 'count' => Country::where('status', 1)->count()],
            'genders' => ['name' => 'Genders', 'count' => Gender::where('status', 1)->count()],
            'languages' => ['name' => 'Languages', 'count' => Language::where('status', 1)->count()],
            'religions' => ['name' => 'Religions', 'count' => Religion::where('status', 1)->count()],
            'marital-statuses' => ['name' => 'Marital Statuses', 'count' => MaritalStatus::where('status', 1)->count()],
            'options' => ['name' => 'Positions (Options)', 'count' => Option::where('status', 1)->count()],
        ];

        return view('webapp.settings.index', compact('categories'));
    }

    /**
     * Display items in a category
     */
    public function showCategory($category)
    {
        if (!isset($this->categoryMap[$category])) {
            return redirect()->route('admin.settings.index')->with('error', 'Invalid category.');
        }

        $modelClass = $this->categoryMap[$category];
        $items = $modelClass::where('status', 1)->orderBy('id', 'desc')->paginate(20);

        $categoryName = $this->getCategoryName($category);

        return view('webapp.settings.category', compact('items', 'category', 'categoryName'));
    }

    /**
     * Show form to create new item
     */
    public function createItem($category)
    {
        if (!isset($this->categoryMap[$category])) {
            return redirect()->route('admin.settings.index')->with('error', 'Invalid category.');
        }

        $categoryName = $this->getCategoryName($category);

        return view('webapp.settings.create', compact('category', 'categoryName'));
    }

    /**
     * Store new item
     */
    public function storeItem(Request $request, $category)
    {
        if (!isset($this->categoryMap[$category])) {
            return redirect()->route('admin.settings.index')->with('error', 'Invalid category.');
        }

        $modelClass = $this->categoryMap[$category];

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Determine the name field based on model
        $nameField = $this->getNameField($category);

        $item = new $modelClass();
        $item->$nameField = $request->name;
        $item->status = 1;
        $item->save();

        return redirect()->route('admin.settings.category', $category)->with('success', 'Item created successfully.');
    }

    /**
     * Show form to edit item
     */
    public function editItem($category, $id)
    {
        if (!isset($this->categoryMap[$category])) {
            return redirect()->route('admin.settings.index')->with('error', 'Invalid category.');
        }

        $modelClass = $this->categoryMap[$category];
        $item = $modelClass::findOrFail($id);

        $categoryName = $this->getCategoryName($category);
        $nameField = $this->getNameField($category);

        return view('webapp.settings.edit', compact('item', 'category', 'categoryName', 'nameField'));
    }

    /**
     * Update item
     */
    public function updateItem(Request $request, $category, $id)
    {
        if (!isset($this->categoryMap[$category])) {
            return redirect()->route('admin.settings.index')->with('error', 'Invalid category.');
        }

        $modelClass = $this->categoryMap[$category];
        $item = $modelClass::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $nameField = $this->getNameField($category);
        $item->$nameField = $request->name;
        $item->save();

        return redirect()->route('admin.settings.category', $category)->with('success', 'Item updated successfully.');
    }

    /**
     * Delete item (soft delete)
     */
    public function destroyItem($category, $id)
    {
        if (!isset($this->categoryMap[$category])) {
            return redirect()->route('admin.settings.index')->with('error', 'Invalid category.');
        }

        $modelClass = $this->categoryMap[$category];
        $item = $modelClass::findOrFail($id);
        $item->status = 0;
        $item->save();

        return redirect()->route('admin.settings.category', $category)->with('success', 'Item deleted successfully.');
    }

    /**
     * Get category display name
     */
    protected function getCategoryName($category)
    {
        $names = [
            'countries' => 'Countries',
            'genders' => 'Genders',
            'languages' => 'Languages',
            'religions' => 'Religions',
            'marital-statuses' => 'Marital Statuses',
            'options' => 'Positions (Options)',
        ];

        return $names[$category] ?? ucfirst($category);
    }

    /**
     * Get the name field for the model
     */
    protected function getNameField($category)
    {
        // Most models use 'name', but some might use different fields
        // Adjust based on your database schema
        $fields = [
            'countries' => 'name',
            'genders' => 'name',
            'languages' => 'name',
            'religions' => 'name',
            'marital-statuses' => 'name',
            'options' => 'name', // Adjust if Option uses a different field
        ];

        return $fields[$category] ?? 'name';
    }
}

