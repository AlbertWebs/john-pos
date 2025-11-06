@extends('layouts.app')

@section('title', 'Users Management')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Users Management</h1>
        <button onclick="showAddUserModal()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Add New User
        </button>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Username</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Login Attempts</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Account Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Attempt</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($users as $user)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ $user->name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $user->username }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $user->role === 'super_admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                            {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $user->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ ucfirst($user->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $user->login_attempts >= 3 ? 'bg-red-100 text-red-800' : ($user->login_attempts > 0 ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                            {{ $user->login_attempts ?? 0 }} / 3
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        @if($user->isLocked())
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                Locked ({{ $user->getRemainingLockTime() }})
                            </span>
                        @else
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                Active
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $user->last_login_attempt ? $user->last_login_attempt->diffForHumans() : 'Never' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <button onclick="showEditUserModal({{ $user->id }})" class="text-indigo-600 hover:text-indigo-900">Edit</button>
                            <button onclick="showResetPinModal({{ $user->id }}, '{{ $user->name }}')" class="text-blue-600 hover:text-blue-900">Reset PIN</button>
                            @if($user->isLocked())
                            <form action="{{ route('admin.users.unlock', $user->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-green-600 hover:text-green-900">Unlock</button>
                            </form>
                            @endif
                            @if($user->login_attempts > 0)
                            <form action="{{ route('admin.users.reset-attempts', $user->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-yellow-600 hover:text-yellow-900">Reset Attempts</button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Add User Modal -->
    <div id="addUserModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 lg:w-1/3 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Add New User</h3>
                    <button onclick="closeAddUserModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form id="addUserForm" method="POST" action="{{ route('admin.users.store') }}">
                    @csrf
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Name *</label>
                        <input type="text" name="name" id="name" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter full name">
                    </div>

                    <div class="mb-4">
                        <label for="username" class="block text-sm font-medium text-gray-700 mb-2">Username *</label>
                        <input type="text" name="username" id="username" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter username">
                        <p class="text-xs text-gray-500 mt-1">Must be unique</p>
                    </div>

                    <div class="mb-4">
                        <label for="pin" class="block text-sm font-medium text-gray-700 mb-2">PIN (4 digits) *</label>
                        <input type="text" name="pin" id="pin" maxlength="4" minlength="4" pattern="[0-9]{4}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter 4-digit PIN" inputmode="numeric">
                        <p class="text-xs text-gray-500 mt-1">Enter exactly 4 digits (0-9)</p>
                        <p class="text-xs text-red-500 mt-1" id="pinErrorAdd" style="display: none;">PIN must be exactly 4 digits</p>
                    </div>

                    <div class="mb-4">
                        <label for="role" class="block text-sm font-medium text-gray-700 mb-2">Role *</label>
                        <select name="role" id="role" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Select Role</option>
                            <option value="cashier">Cashier</option>
                            <option value="super_admin">Super Admin</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                        <select name="status" id="status" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeAddUserModal()" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Create User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div id="editUserModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 lg:w-1/3 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Edit User</h3>
                    <button onclick="closeEditUserModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form id="editUserForm" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="user_id" id="edit_user_id">
                    
                    <div class="mb-4">
                        <label for="edit_name" class="block text-sm font-medium text-gray-700 mb-2">Name *</label>
                        <input type="text" name="name" id="edit_name" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter full name">
                    </div>

                    <div class="mb-4">
                        <label for="edit_username" class="block text-sm font-medium text-gray-700 mb-2">Username *</label>
                        <input type="text" name="username" id="edit_username" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter username">
                        <p class="text-xs text-gray-500 mt-1">Must be unique</p>
                    </div>

                    <div class="mb-4">
                        <label for="edit_pin" class="block text-sm font-medium text-gray-700 mb-2">PIN (4 digits)</label>
                        <input type="text" name="pin" id="edit_pin" maxlength="4" minlength="4" pattern="[0-9]{4}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Leave blank to keep current PIN" inputmode="numeric">
                        <p class="text-xs text-gray-500 mt-1">Enter exactly 4 digits (0-9) or leave blank to keep current PIN</p>
                        <p class="text-xs text-red-500 mt-1" id="pinErrorEdit" style="display: none;">PIN must be exactly 4 digits</p>
                    </div>

                    <div class="mb-4">
                        <label for="edit_role" class="block text-sm font-medium text-gray-700 mb-2">Role *</label>
                        <select name="role" id="edit_role" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Select Role</option>
                            <option value="cashier">Cashier</option>
                            <option value="super_admin">Super Admin</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="edit_status" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                        <select name="status" id="edit_status" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeEditUserModal()" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Update User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Reset PIN Modal -->
    <div id="resetPinModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 lg:w-1/3 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900" id="modalTitle">Reset PIN</h3>
                    <button onclick="closeResetPinModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form id="resetPinForm" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="new_pin" class="block text-sm font-medium text-gray-700 mb-2">New PIN (4 digits) *</label>
                        <input type="text" name="new_pin" id="new_pin" maxlength="4" minlength="4" pattern="[0-9]{4}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter 4-digit PIN" inputmode="numeric">
                        <p class="text-xs text-gray-500 mt-1">Enter exactly 4 digits (0-9)</p>
                        <p class="text-xs text-red-500 mt-1" id="pinError" style="display: none;">PIN must be exactly 4 digits</p>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeResetPinModal()" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Reset PIN</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function showAddUserModal() {
            document.getElementById('addUserForm').reset();
            document.getElementById('addUserModal').classList.remove('hidden');
        }

        function closeAddUserModal() {
            document.getElementById('addUserModal').classList.add('hidden');
        }

        async function showEditUserModal(userId) {
            try {
                const response = await fetch(`/admin/users/${userId}`);
                const user = await response.json();
                
                document.getElementById('edit_user_id').value = user.id;
                document.getElementById('edit_name').value = user.name;
                document.getElementById('edit_username').value = user.username;
                document.getElementById('edit_pin').value = '';
                document.getElementById('edit_role').value = user.role;
                document.getElementById('edit_status').value = user.status;
                document.getElementById('editUserForm').action = `/admin/users/${userId}`;
                document.getElementById('editUserModal').classList.remove('hidden');
            } catch (error) {
                console.error('Error fetching user:', error);
                alert('Failed to load user data. Please try again.');
            }
        }

        function closeEditUserModal() {
            document.getElementById('editUserModal').classList.add('hidden');
            document.getElementById('editUserForm').reset();
        }

        function showResetPinModal(userId, userName) {
            document.getElementById('modalTitle').textContent = 'Reset PIN for ' + userName;
            document.getElementById('resetPinForm').action = '/admin/users/' + userId + '/reset-pin';
            document.getElementById('new_pin').value = '';
            document.getElementById('resetPinModal').classList.remove('hidden');
        }

        function closeResetPinModal() {
            document.getElementById('resetPinModal').classList.add('hidden');
        }

        // Validate PIN input for reset PIN modal
        const pinInput = document.getElementById('new_pin');
        const pinError = document.getElementById('pinError');
        const resetPinForm = document.getElementById('resetPinForm');
        
        if (pinInput) {
            pinInput.addEventListener('input', function(e) {
                // Remove any non-numeric characters
                this.value = this.value.replace(/[^0-9]/g, '');
                
                // Limit to 4 digits
                if (this.value.length > 4) {
                    this.value = this.value.slice(0, 4);
                }
                
                // Show/hide error message
                if (this.value.length > 0 && this.value.length !== 4) {
                    pinError.style.display = 'block';
                } else {
                    pinError.style.display = 'none';
                }
            });
            
            // Validate on form submit
            resetPinForm?.addEventListener('submit', function(e) {
                if (pinInput.value.length !== 4) {
                    e.preventDefault();
                    pinError.style.display = 'block';
                    pinInput.focus();
                    return false;
                }
            });
        }

        // Validate PIN input for add user modal
        const pinInputAdd = document.getElementById('pin');
        const pinErrorAdd = document.getElementById('pinErrorAdd');
        const addUserForm = document.getElementById('addUserForm');
        
        if (pinInputAdd) {
            pinInputAdd.addEventListener('input', function(e) {
                // Remove any non-numeric characters
                this.value = this.value.replace(/[^0-9]/g, '');
                
                // Limit to 4 digits
                if (this.value.length > 4) {
                    this.value = this.value.slice(0, 4);
                }
                
                // Show/hide error message
                if (this.value.length > 0 && this.value.length !== 4) {
                    pinErrorAdd.style.display = 'block';
                } else {
                    pinErrorAdd.style.display = 'none';
                }
            });
            
            // Validate on form submit
            addUserForm?.addEventListener('submit', function(e) {
                if (pinInputAdd.value.length !== 4) {
                    e.preventDefault();
                    pinErrorAdd.style.display = 'block';
                    pinInputAdd.focus();
                    return false;
                }
            });
        }

        // Validate PIN input for edit user modal
        const pinInputEdit = document.getElementById('edit_pin');
        const pinErrorEdit = document.getElementById('pinErrorEdit');
        const editUserForm = document.getElementById('editUserForm');
        
        if (pinInputEdit) {
            pinInputEdit.addEventListener('input', function(e) {
                // Remove any non-numeric characters
                this.value = this.value.replace(/[^0-9]/g, '');
                
                // Limit to 4 digits
                if (this.value.length > 4) {
                    this.value = this.value.slice(0, 4);
                }
                
                // Show/hide error message (only if user entered something)
                if (this.value.length > 0 && this.value.length !== 4) {
                    pinErrorEdit.style.display = 'block';
                } else {
                    pinErrorEdit.style.display = 'none';
                }
            });
            
            // Validate on form submit (only if PIN is provided)
            editUserForm?.addEventListener('submit', function(e) {
                const pinValue = pinInputEdit.value.trim();
                if (pinValue.length > 0 && pinValue.length !== 4) {
                    e.preventDefault();
                    pinErrorEdit.style.display = 'block';
                    pinInputEdit.focus();
                    return false;
                }
            });
        }
    </script>
</div>
@endsection

