<div class="table-wrap">
    <table>
        <thead><tr><th>Nama Siswa</th><th>{{ __('Aksi') }}</th></tr></thead>
        <tbody>
            @forelse($students as $student)
            <tr>
                <td class="font-semibold">
                    <div style="display:flex;align-items:center;">
                        <div style="width:28px;height:28px;border-radius:50%;background:#dbeafe;color:#2563eb;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:0.7rem;margin-right:8px;">{{ strtoupper(substr($student->name,0,2)) }}</div>
                        {{ $student->name }}
                    </div>
                </td>
                <td>
                    <form method="POST" action="{{ route('teacher.courses.remove-student', [$course, $student]) }}" style="margin:0;">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('{{ __('app.remove_student_confirm') }}')">{{ __('app.remove') }}</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="2" class="text-center text-muted" style="padding:20px;">{{ __('Belum ada siswa yang mendaftar di kelas ini.') }}</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div style="padding: 16px; border-top: 1px solid var(--border);">
    {{ $students->links('pagination::bootstrap-4') }}
</div>
