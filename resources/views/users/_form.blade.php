    @csrf

    <!-- Nama -->
    <div class="col-md-12">
        <label for="validationServerName" class="form-label">Nama</label>
        <input type="text" name="name" id="validationServerName" 
               class="form-control @error('name') is-invalid @enderror" 
               value="{{ old('name', $user->name ?? '') }}">
        @error('name')
            <div id="validationServerNameFeedback" class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    <!-- Email -->
    <div class="col-md-12">
        <label for="validationServerEmail" class="form-label">Email</label>
        <input type="email" name="email" id="validationServerEmail" 
               class="form-control @error('email') is-invalid @enderror" 
               value="{{ old('email', $user->email ?? '') }}">
        @error('email')
            <div id="validationServerEmailFeedback" class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    <!-- Password -->
    <div class="col-md-12">
        <label for="validationServerPassword" class="form-label">Password</label>
        <input type="password" name="password" id="validationServerPassword" 
               class="form-control @error('password') is-invalid @enderror">
        @error('password')
            <div id="validationServerPasswordFeedback" class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    <!-- Role -->
    <div class="col-md-12">
        <label for="validationServerRole" class="form-label">Role</label>
        <select id="validationServerRole" name="role_id" 
                class="form-select @error('role_id') is-invalid @enderror">
            <option value="">-- Pilih Role --</option>
            @foreach($roles as $role)
                <option value="{{ $role->id }}" @selected(old('role_id', $user->role_id ?? '') == $role->id)>
                    {{ ucfirst($role->name) }}
                </option>
            @endforeach
        </select>
        @error('role_id')
            <div id="validationServerRoleFeedback" class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    <!-- Tombol Aksi -->
    <div class="col-12 mt-4">
        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="{{ route('admin.users') }}" class="btn btn-secondary">Kembali</a>
    </div>
</form>