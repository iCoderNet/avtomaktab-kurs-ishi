<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\Instructor;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public function index(Request $request)
    {
        $vehicles = Vehicle::with('assignedInstructor.user')
            ->when($request->search, fn($q) => $q->where('plate_number', 'like', '%'.$request->search.'%')
                ->orWhere('brand', 'like', '%'.$request->search.'%'))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->latest()->paginate(15)->withQueryString();
        $instructors = Instructor::with('user')->where('status', 'active')->get();
        return view('admin.vehicles.index', compact('vehicles', 'instructors'));
    }

    public function create()
    {
        $instructors = Instructor::with('user')->where('status', 'active')->get();
        return view('admin.vehicles.create', compact('instructors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'plate_number' => 'required|unique:vehicles',
            'brand' => 'required|string',
            'model' => 'required|string',
            'year' => 'required|integer|min:1990|max:'.(date('Y')+1),
        ]);
        Vehicle::create($request->except('_token'));
        return redirect()->route('admin.vehicles.index')->with('success', "Avtomobil qo'shildi!");
    }

    public function show(Vehicle $vehicle)
    {
        $vehicle->load('assignedInstructor.user', 'lessons');
        return view('admin.vehicles.show', compact('vehicle'));
    }

    public function edit(Vehicle $vehicle)
    {
        $instructors = Instructor::with('user')->where('status', 'active')->get();
        return view('admin.vehicles.edit', compact('vehicle', 'instructors'));
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        $request->validate(['plate_number' => 'required|unique:vehicles,plate_number,'.$vehicle->id]);
        $vehicle->update($request->except(['_token','_method']));
        return redirect()->route('admin.vehicles.index')->with('success', "Avtomobil yangilandi!");
    }

    public function destroy(Vehicle $vehicle)
    {
        $vehicle->delete();
        return redirect()->route('admin.vehicles.index')->with('success', "Avtomobil o'chirildi.");
    }
}
