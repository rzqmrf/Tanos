<div class="border border-slate-200/60 dark:border-slate-800 bg-white dark:bg-slate-900 rounded-xl p-4 shadow-sm" style="margin-left: {{ $depth * 1.5 }}rem;">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
        <div class="flex items-start gap-3">
            <div class="mt-0.5 text-slate-400">
                @if($node->children->isNotEmpty())
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4 text-indigo-500">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                    </svg>
                @else
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 text-slate-300">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                @endif
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-xs font-bold text-slate-800 dark:text-slate-200">{{ $node->wbs_code }}</span>
                    <span class="px-2 py-0.5 rounded text-[9px] font-extrabold uppercase {{ $node->sent_to_sap ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/20 dark:text-emerald-400' : 'bg-amber-50 text-amber-700 dark:bg-amber-950/20 dark:text-amber-400' }}">
                        {{ $node->sent_to_sap ? 'SAP Created' : 'Draft' }}
                    </span>
                    <span class="px-2 py-0.5 rounded text-[9px] font-bold bg-indigo-50 text-indigo-700 dark:bg-indigo-950/20 dark:text-indigo-400">
                        {{ $node->wbs_category }}
                    </span>
                </div>
                <h4 class="text-xs font-semibold text-slate-600 dark:text-slate-300 mt-1">{{ $node->wbs_name }}</h4>
                <div class="flex items-center gap-3 text-[10px] text-slate-400 mt-1 font-medium">
                    <span>Bobot: <strong>{{ $node->weight }}%</strong></span>
                    @if($node->expected_start && $node->expected_end)
                        <span>•</span>
                        <span>Rentang: {{ $node->expected_start->format('d M Y') }} - {{ $node->expected_end->format('d M Y') }}</span>
                    @endif
                </div>
            </div>
        </div>

        @if(in_array(session('user.role'), ['Admin', 'Project Manager', 'Finance Manager']))
        <div class="flex items-center gap-1.5 self-end md:self-auto">
            <button @click="openAddModal('{{ $node->id }}', '{{ $node->wbs_name }}')" 
                    class="px-2.5 py-1.5 bg-slate-50 dark:bg-slate-800 text-[10px] font-bold text-indigo-600 dark:text-indigo-400 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-750 transition cursor-pointer">
                + Add Sub
            </button>
            <button @click="openEditModal(
                '{{ $node->id }}', 
                '{{ $node->wbs_code }}', 
                '{{ $node->wbs_name }}', 
                '{{ $node->wbs_category }}', 
                {{ $node->weight }}, 
                '{{ $node->expected_start ? $node->expected_start->format('Y-m-d') : '' }}', 
                '{{ $node->expected_end ? $node->expected_end->format('Y-m-d') : '' }}'
            )" 
                    class="px-2.5 py-1.5 bg-slate-50 dark:bg-slate-800 text-[10px] font-bold text-slate-600 dark:text-slate-350 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-750 transition cursor-pointer">
                Edit
            </button>
            <form action="{{ route('projects.wbs.destroy', [$node->project_id, $node->id]) }}" method="POST" onsubmit="return confirm('Yakin hapus WBS ini dan seluruh sub-strukturnya?')" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-2.5 py-1.5 bg-rose-50 dark:bg-rose-950/20 text-[10px] font-bold text-rose-600 dark:text-rose-400 rounded-lg hover:bg-rose-100 dark:hover:bg-rose-950/40 transition cursor-pointer">
                    Hapus
                </button>
            </form>
        </div>
        @endif
    </div>
</div>

@if($node->children->isNotEmpty())
    <div class="space-y-3 mt-3">
        @foreach($node->children as $child)
            @include('operations.wbs_node', ['node' => $child, 'depth' => $depth + 1])
        @endforeach
    </div>
@endif
