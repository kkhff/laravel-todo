<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>My Daily Task - Laravel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #dedede; }
        [x-cloak] { display: none !important; }
        .progress-glow {
        box-shadow: 0 0 15px rgba(37, 99, 235, 0.3);
        }   
        input[type="checkbox"] {
            appearance: none;
            background-color: #fff;
            margin: 0;
            font: inherit;
            color: #2563eb;
            width: 1.25em;
            height: 1.25em;
            border: 2px solid #cbd5e1;
            border-radius: 0.35em;
            display: grid;
            place-content: center;
            cursor: pointer;
            transition: all 0.15s ease-in-out;
        }
        input[type="checkbox"]::before {
            content: "";
            width: 0.65em;
            height: 0.65em;
            transform: scale(0);
            transition: 120ms transform ease-in-out;
            box-shadow: inset 1em 1em white;
            clip-path: polygon(14% 44%, 0 65%, 50% 100%, 100% 16%, 80% 0%, 43% 62%);
        }
        input[type="checkbox"]:checked { background-color: #2563eb; border-color: #2563eb; }
        input[type="checkbox"]:checked::before { transform: scale(1); }

    </style>
</head>
<body class="antialiased text-slate-800"
    x-data="todoApp()">
    

    <div class="max-w-2xl mx-auto px-4 py-12">
        <!--- Judul Utama --->
        <header class="mb-10">
            <div class="flex items-end justify-between mb-6">
                <div>
                    <h1 class="text-4xl font-extrabold text-slate-900 tracking-tight mb-2">Daftar Tugas</h1>
                    <p class="text-slate-500 text-lg">Kelola produktivitas Anda dengan mudah</p>
                </div>
                <div class="text-right">
                    <span class="text-3xl font-black text-blue-600" x-text="percentage + '%'"></span>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Selesai</p>
                </div>
            </div>

            <div class="w-full h-4 bg-slate-200 rounded-full overflow-hidden p-1">
                <div class="h-full bg-blue-600 rounded-full transition-all duration-1000 ease-out progress-glow"
                    :style="'width: ' + percentage + '%'"></div>
            </div>
            <div class="flex justify-between mt-2 px-1">
                <span class="text-xs font-bold text-slate-500"><span x-text="completedCount"></span> Selesai </span>
                <span class="text-xs font-bold text-slate-500"><span x-text="totalCount - completedCount"></span> Tersisa </span>
            </div>
        </header>

        <!--- Form Tambah Tugas Baru --->
        <section class="bg-white rounded-3xl shadow-sm border border-slate-200 p-6 mb-10">
            <form @submit.prevent="saveTodo()" class="space-y-4">
                <div>
                    <input type="text" x-model="newTitle" placeholder="Apa rencana Anda hari ini?" required
                    class="w-full px-5 py-3 rounded-2xl border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all placeholder:text-slate-400">
                </div>
                <div>
                    <textarea x-model="newDescription" placeholder="Deskripsi atau catatan tambahan (Opsional)" rows="2"
                    class="w-full px-5 py-3 rounded-2xl border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all placeholder:text-slate-400"></textarea>
                </div>
                <button type="submit" :disabled="isSubmitting"
                    class="w-full bg-blue-600 hover:bg-blue-700 disabled:bg-slate-400 text-white font-bold py-3.5 rounded-2xl shadow-lg transition-all flex items-center justify-center gap-2">
                    <span x-show="!isSubmitting" class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                        Simpan Tugas Baru
                    </span>
                    <span x-show="isSubmitting" x-cloak class="flex items-center gap-2">
                        <svg class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Sedang Menyimpan...
                    </span>
                </button>
            </form>
        </section>

        <!--- Daftar List Todo --->
        <div class="space-y-4">
            <template x-for="todo in sortedTodos" :key="todo.id">
                <div class="group bg-white p-5 rounded-3xl border border-slate-200 shadow-sm flex items-start justify-between hover:border-blue-300 transition-all">
                    <div class="flex items-start gap-4 flex-grow">
                        <!--- Form Checklist --->
                        <input type="checkbox"
                                x-model="todo.is_completed"
                                @change="toggleTodo(todo)">

                        <div class="flex-grow">
                            <h3 class="font-bold text-lg leading-tight transition-all"
                                :class="todo.is_completed ? 'line-through text-slate-400' : 'text-slate-800'"
                                x-text="todo.title">
                            </h3>
                            <p x-show="todo.description"
                                class="text-sm text-slate-500 mt-1 transition-all"
                                :class="todo.is_completed ? 'line-through' : ''"
                                x-text="todo.description">
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-all">
                        <!--- Tombol Modal Edit --->
                        <button @click="openEdit(todo)" class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2.5 2.5 0 113.536 3.536L12 14.732 3 21.036H3v-3.572L16.732 3.732z"></path></svg>
                        </button>

                        <!--- Form Hapus Tugas --->
                        <button type="button" @click="confirmDelete(todo.id)" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </div>
                </div>
            </template>

            <template x-if="todos.length === 0">
                <!--- Kondisi Data Kosong --->
                <div class="text-center py-20 bg-white rounded-3xl border-2 border-dashed border-slate-300">
                    <p class="text-slate-400 font-medium italic">Belum ada rencana tugas hari ini</p>
                </div>

            </template>
        </div>
    </div>

    <!--- Modal Edit --->
    <div x-show="editModalOpen"
        class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak>
        <!--- Overlay --->
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="editModalOpen = false"></div>

        <!--- Box Modal --->
        <div class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-lg p-8 relative z-10"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100">

            <div class="flex items-center justify-between mb-8">
                <h3 class="text-2xl font-bold text-slate-900 tracking-tight">Perbarui Tugas</h3>
                <button @click="editModalOpen = false" class="p-2 bg-slate-50 text-slate-400 hover:text-slate-600 rounded-full transition-all">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <form @submit.prevent="updateTodo()" class="space-y-5">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2 px-1">Judul Tugas</label>
                    <input type="text" name="title" x-model="editTitle" required 
                        class="w-full px-5 py-3.5 rounded-2xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none bg-slate-50 focus:bg-white transition-all">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2 px-1">Deskripsi Tambahan</label>
                    <textarea name="description" x-model="editDescription" rows="4"
                        class="w-full px-5 py-3.5 rounded-2xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none bg-slate-50 focus:bg-white transition-all"></textarea>
                </div>
                <div class="flex gap-4 pt-4">
                    <button type="button" @click="editModalOpen = false" 
                            class="flex-1 px-6 py-4 border-2 border-slate-100 text-slate-600 font-bold rounded-2xl hover:bg-slate-50 transition-all">
                        Batal
                    </button>
                    <button type="submit" 
                            class="flex-1 px-6 py-4 bg-blue-600 text-white font-bold rounded-2xl hover:bg-blue-700 shadow-lg shadow-blue-200 transition-all">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('todoApp', () => ({
                baseUrl: '/api/v1/todos',
                todos: [],
                newTitle: '',
                newDescription: '',
                editId: '',
                editTitle:'',
                editDescription:'',
                editRoute:'',
                editModalOpen: false,
                isSubmitting: false,

                get sortedTodos() {
                    return [...this.todos].sort((a,b) => {
                        if (a.is_completed !== b.is_completed) {
                            return a.is_completed - b.is_completed;
                        }

                        return b.id - a.id;
                    });
                },

                get completedCount() {
                    return this.todos.filter(t => t.is_completed).length;
                },

                get totalCount() {
                    return this.todos.length;
                },

                get percentage() {
                    return this.totalCount > 0 ? Math.round((this.completedCount / this.totalCount) * 100) : 0;
                    },

                openEdit(todo) {
                    this.editId = todo.id;
                    this.editTitle = todo.title;
                    this.editDescription = todo.description || '';
                    this.editModalOpen = true;
                },

                async init() {
                    try {
                        const response = await fetch(this.baseUrl);
                        const data = await response.json();
                        this.todos = data.todos;
                    } catch (error) {
                        console.error('Gagal mengambil data:', error);
                    }
                },

                async updateTodo() {
                    try {
                        const response = await fetch(`${this.baseUrl}/${this.editId}`, {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                title: this.editTitle,
                                description: this.editDescription
                            })
                        });

                        if (response.ok) {
                            const data = await response.json();

                            this.todos = this.todos.map(t => {
                                if (t.id === this.editId) {
                                    return data.todo;
                                }
                                return t;
                            });

                            this.editModalOpen = false;

                            Swal.fire({
                                title: 'Berhasil!',
                                text: 'Tugas diperbarui',
                                icon: 'success',
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000
                            });
                        } else {
                            throw new Error();
                        }  
                    } catch (error) {
                        Swal.fire('Error', 'Gagal memperbarui data', 'error');
                    }
                },

                async saveTodo() {
                    if (!this.newTitle) return;
                    this.isSubmitting = true;

                    try {
                        const response = await fetch(this.baseUrl, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                title: this.newTitle,
                                description: this.newDescription
                            })
                        });

                        if (response.ok) {
                            const data = await response.json();

                            this.todos.unshift(data.todo);

                            this.newTitle = '';
                            this.newDescription = '';

                            Swal.fire({
                                title : 'Berhasil!',
                                text : 'Tugas ditambahkan',
                                icon : 'success',
                                toast : true,
                                position : 'top-end',
                                showConfirmButton : false,
                                timer : 3000
                            });
                        } else {
                            throw new Error('Gagal menyimpan');    
                        }
                    } catch (error) {
                        Swal.fire('Error', error.message, 'error'); 
                    } finally {
                        this.isSubmitting = false;
                    }
                },

                async toggleTodo(todo) {

                    const originalStatus = !todo.is_completed;

                    try {
                        const response = await fetch(`${this.baseUrl}/${todo.id}/toggle`, {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            }
                        });

                        if(!response.ok) throw new Error('Server error');

                        const data = await response.json();

                        todo.is_completed = data.is_completed;
                        
                    } catch (error) {
                        todo.is_completed = originalStatus;

                        Swal.fire({
                            title: 'Error',
                            text: 'Gagal memperbarui status tugas',
                            icon: 'error',
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000
                        });

                    }   
                },
                confirmDelete(id) {
                    Swal.fire({
                        title: 'Hapus tugas ini?',
                        text: 'Data yang dihapus tidak dapat dikembalikan!',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        cancelButtonColor: '#64748b',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then(async (result) => {
                        if (result.isConfirmed) {
                            try {
                                const response = await fetch(`${this.baseUrl}/${id}`, {
                                    method: 'DELETE',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'Accept': 'application/json'
                                    }
                                });

                                if (response.ok) {
                                    this.todos = this.todos.filter(t => t.id !== id);
                                    Swal.fire({
                                        title: 'Berhasil!',
                                        text: 'Tugas telah dihapus',
                                        icon: 'success',
                                        toast: true,
                                        position: 'top-end',
                                        showConfirmButton: false,
                                        timer: 3000
                                    });
                                } else {
                                    throw new Error();
                                }
                            } catch (e) {
                                Swal.fire('Error', 'Gagal menghapus data', 'error');
                            }
                        }
                    });
               }
            }));
        });
    
    </script>
</body>
</html>