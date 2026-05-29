@php
    $projectsJson = ($projects ?? collect())->map(fn ($p) => [
        'id' => $p->id,
        'name' => $p->name,
        'client_id' => $p->client_id,
    ])->values();
    $selectedProjectId = old('project_id', $selectedProjectId ?? null);
@endphp
<script>
(function () {
    const projects = @json($projectsJson);
    const clientSelect = document.getElementById('client_id');
    const projectSelect = document.getElementById('project_id');
    if (!clientSelect || !projectSelect) return;

    function fillProjects(clientId) {
        const current = projectSelect.value;
        projectSelect.innerHTML = '<option value="">— بدون مشروع / عام —</option>';
        projects
            .filter(p => String(p.client_id) === String(clientId))
            .forEach(p => {
                const opt = document.createElement('option');
                opt.value = p.id;
                opt.textContent = p.name;
                projectSelect.appendChild(opt);
            });
        if (current && [...projectSelect.options].some(o => o.value === current)) {
            projectSelect.value = current;
        }
    }

    clientSelect.addEventListener('change', () => fillProjects(clientSelect.value));
    fillProjects(clientSelect.value);
    @if($selectedProjectId)
    projectSelect.value = '{{ $selectedProjectId }}';
    @endif
})();
</script>
