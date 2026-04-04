<section>
    <header style="margin-bottom: 24px;">
        <h2 class="card-title">
            {{ __('app.profile') ?? 'Profile Information' }}
        </h2>
        <p class="text-sm text-muted">
            {{ __('app.profile_update_msg') }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div class="grid grid-2 gap-4" style="margin-bottom: 20px;">
            <div class="form-group">
                <label for="photo">{{ __('app.profile_photo') }}</label>
                <input id="photo" name="photo" type="file" accept="image/jpeg,image/jpg,image/png,image/gif,image/webp" />
                <small class="text-muted" style="display: block; margin-top: 4px; font-size: 0.8rem;">
                    📷 Semua ukuran & resolusi diterima — foto akan otomatis dikompres oleh sistem.
                </small>
            </div>

            <div class="form-group">
                <label for="email">Email <span class="text-danger">*</span></label>
                <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required autocomplete="username" {{ auth()->user()->isAdmin() ? '' : 'readonly title="Hubungi administrator jika Anda perlu mengubah alamat email" style="background-color: var(--bg-color-alt); cursor: not-allowed;"' }} />
                @if($errors->has('email'))<div class="form-error">{{ $errors->first('email') }}</div>@endif
                @if(!auth()->user()->isAdmin())
                    <small class="text-muted" style="display: block; margin-top: 4px; font-size: 0.8rem;">Hubungi administrator jika Anda perlu mengubah alamat email.</small>
                @endif
            </div>

            <div class="form-group">
                <label for="name">Nama Lengkap <span class="text-danger">*</span></label>
                <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
                @if($errors->has('name'))<div class="form-error">{{ $errors->first('name') }}</div>@endif
            </div>

            <div class="form-group">
                <label for="nis">NIS / NIP</label>
                <input id="nis" name="nis" type="text" value="{{ old('nis', $user->nis) }}" placeholder="Contoh: 001837" />
                @if($errors->has('nis'))<div class="form-error">{{ $errors->first('nis') }}</div>@endif
            </div>

            <div class="form-group">
                <label for="gender">Jenis Kelamin</label>
                <select id="gender" name="gender">
                    <option value="">Pilih Jenis Kelamin</option>
                    <option value="Laki-laki" {{ old('gender', $user->gender) === 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="Perempuan" {{ old('gender', $user->gender) === 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                </select>
                @if($errors->has('gender'))<div class="form-error">{{ $errors->first('gender') }}</div>@endif
            </div>

            <div class="form-group">
                <label for="religion">Agama</label>
                <input id="religion" name="religion" type="text" value="{{ old('religion', $user->religion) }}" placeholder="Contoh: Islam, Kristen, dll." />
                @if($errors->has('religion'))<div class="form-error">{{ $errors->first('religion') }}</div>@endif
            </div>

            <div class="form-group">
                <label for="birth_place">Tempat Lahir</label>
                <input id="birth_place" name="birth_place" type="text" value="{{ old('birth_place', $user->birth_place) }}" placeholder="Contoh: Bandung" />
                @if($errors->has('birth_place'))<div class="form-error">{{ $errors->first('birth_place') }}</div>@endif
            </div>

            <div class="form-group">
                <label for="birth_date">Tanggal Lahir</label>
                <input id="birth_date" name="birth_date" type="date" value="{{ old('birth_date', $user->birth_date) }}" />
                @if($errors->has('birth_date'))<div class="form-error">{{ $errors->first('birth_date') }}</div>@endif
            </div>

            <div class="form-group" style="grid-column: span 2;">
                <label for="address">Alamat Tempat Tinggal</label>
                <textarea id="address" name="address" rows="2" placeholder="Contoh: Jalan Cendana No. 7, Cimahi, Jawa Barat">{{ old('address', $user->address) }}</textarea>
                @if($errors->has('address'))<div class="form-error">{{ $errors->first('address') }}</div>@endif
            </div>

            <div class="form-group" style="grid-column: span 2;">
                <label for="phone">No. Telepon/Hp</label>
                <input id="phone" name="phone" type="text" value="{{ old('phone', $user->phone) }}" placeholder="Contoh: 081234567890" />
                @if($errors->has('phone'))<div class="form-error">{{ $errors->first('phone') }}</div>@endif
            </div>
        </div>

        <h3 class="font-semibold text-lg mb-4" style="border-bottom: 1px solid var(--border); padding-bottom: 10px; margin-top: 30px;">Data Orang Tua</h3>
        <div class="grid grid-2 gap-4" style="margin-bottom: 20px;">
            <div class="form-group">
                <label for="father_name">Nama Ayah</label>
                <input id="father_name" name="father_name" type="text" value="{{ old('father_name', $user->father_name) }}" placeholder="Contoh: Bambang Wijaya" />
                @if($errors->has('father_name'))<div class="form-error">{{ $errors->first('father_name') }}</div>@endif
            </div>

            <div class="form-group">
                <label for="mother_name">Nama Ibu</label>
                <input id="mother_name" name="mother_name" type="text" value="{{ old('mother_name', $user->mother_name) }}" placeholder="Contoh: Dewi Purnama" />
                @if($errors->has('mother_name'))<div class="form-error">{{ $errors->first('mother_name') }}</div>@endif
            </div>

            <div class="form-group">
                <label for="father_job">Pekerjaan Ayah</label>
                <input id="father_job" name="father_job" type="text" value="{{ old('father_job', $user->father_job) }}" placeholder="Contoh: Wiraswasta" />
                @if($errors->has('father_job'))<div class="form-error">{{ $errors->first('father_job') }}</div>@endif
            </div>

            <div class="form-group">
                <label for="mother_job">Pekerjaan Ibu</label>
                <input id="mother_job" name="mother_job" type="text" value="{{ old('mother_job', $user->mother_job) }}" placeholder="Contoh: Guru SD" />
                @if($errors->has('mother_job'))<div class="form-error">{{ $errors->first('mother_job') }}</div>@endif
            </div>

            <div class="form-group" style="grid-column: span 2;">
                <label for="parent_address">Alamat Orang Tua</label>
                <textarea id="parent_address" name="parent_address" rows="2" placeholder="Contoh: Jalan Cendana No. 7, Cimahi, Jawa Barat">{{ old('parent_address', $user->parent_address) }}</textarea>
                @if($errors->has('parent_address'))<div class="form-error">{{ $errors->first('parent_address') }}</div>@endif
            </div>
        </div>

        <div class="flex flex-center gap-3">
            <button type="submit" class="btn btn-primary">{{ __('app.save_changes') }}</button>

            @if (session('status') === 'profile-updated')
                <p class="text-sm text-success" style="color:var(--success); font-weight: 500;">{{ __('app.saved') }}</p>
            @endif
        </div>
    </form>
</section>
