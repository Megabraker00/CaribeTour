<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Terminal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class TerminalController extends Controller
{
    public function index(): View
    {
        $terminals = Terminal::query()
            ->with('parentTerminal')
            ->orderBy('name')
            ->get();

        return view('admin.terminal.index', compact('terminals'));
    }

    public function create(): View
    {
        return view('admin.terminal.create', [
            'parentOptions' => $this->parentOptions(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatedTerminal($request);

        Terminal::create($validated);

        return redirect()
            ->route('admin.terminals.index')
            ->with('success', 'Terminal creado correctamente.');
    }

    public function edit(Terminal $terminal): View
    {
        return view('admin.terminal.edit', [
            'terminal' => $terminal,
            'parentOptions' => $this->parentOptions(excludeTerminal: $terminal),
        ]);
    }

    public function update(Request $request, Terminal $terminal): RedirectResponse
    {
        $validated = $this->validatedTerminal($request, $terminal);

        if (isset($validated['parent_id']) && (int) $validated['parent_id'] === $terminal->id) {
            return redirect()
                ->route('admin.terminals.edit', $terminal)
                ->withInput()
                ->withErrors(['parent_id' => 'El terminal no puede ser padre de sí mismo.']);
        }

        $parentId = $validated['parent_id'] ?? null;
        if ($parentId !== null && in_array((int) $parentId, $this->descendantTerminalIds($terminal), true)) {
            return redirect()
                ->route('admin.terminals.edit', $terminal)
                ->withInput()
                ->withErrors(['parent_id' => 'No puedes asignar como padre un terminal que depende de este.']);
        }

        $terminal->update($validated);

        return redirect()
            ->route('admin.terminals.index')
            ->with('success', 'Terminal actualizado correctamente.');
    }

    /**
     * @return \Illuminate\Support\Collection<int, Terminal>
     */
    private function parentOptions(?Terminal $excludeTerminal = null)
    {
        $query = Terminal::query()->orderBy('name');

        if ($excludeTerminal) {
            $invalid = array_merge(
                [$excludeTerminal->id],
                $this->descendantTerminalIds($excludeTerminal)
            );
            $query->whereNotIn('id', $invalid);
        }

        return $query->get();
    }

    /**
     * @return list<int>
     */
    private function descendantTerminalIds(Terminal $terminal): array
    {
        $terminal->loadMissing('children');
        $ids = [];
        foreach ($terminal->children as $child) {
            $ids[] = $child->id;
            $ids = array_merge($ids, $this->descendantTerminalIds($child));
        }

        return $ids;
    }

    /**
     * @return array<string, mixed>
     */
    private function validatedTerminal(Request $request, ?Terminal $terminal = null): array
    {
        if (! $request->filled('parent_id')) {
            $request->merge(['parent_id' => null]);
        }

        return $request->validate([
            'name' => 'required|string|max:100',
            'address' => 'nullable|string|max:255',
            'parent_id' => [
                'nullable',
                'integer',
                Rule::exists('terminals', 'id'),
            ],
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'name.max' => 'El nombre no puede superar 100 caracteres.',
            'parent_id.exists' => 'El terminal padre seleccionado no es válido.',
        ]);
    }
}
