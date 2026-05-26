<template>
    <AdminLayout>
        <div class="header">
            <h1>Create Tenant</h1>
            <Link href="/admin/tenants" class="back-link">Back to List</Link>
        </div>

        <form @submit.prevent="submit" class="form">
            <div class="form-group">
                <label>Name</label>
                <input v-model="form.name" type="text" required :class="{ 'has-error': form.errors.name }">
                <div v-if="form.errors.name" class="error">{{ form.errors.name }}</div>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input v-model="form.email" type="email" required :class="{ 'has-error': form.errors.email }">
                <div v-if="form.errors.email" class="error">{{ form.errors.email }}</div>
            </div>
            <div class="form-group">
                <label>Phone</label>
                <input v-model="form.phone" type="text" :class="{ 'has-error': form.errors.phone }">
                <div v-if="form.errors.phone" class="error">{{ form.errors.phone }}</div>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn" :disabled="form.processing">
                    {{ form.processing ? 'Creating...' : 'Create Tenant' }}
                </button>
                <Link href="/admin/tenants" class="cancel">Cancel</Link>
            </div>
        </form>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';

const form = useForm({ name: '', email: '', phone: '' });
const submit = () => { form.post('/admin/tenants'); };
</script>

<style scoped>
.header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
h1 { margin: 0; }
.back-link { color: #3490dc; text-decoration: none; }
.back-link:hover { text-decoration: underline; }
.form { max-width: 600px; }
.form-group { margin-bottom: 15px; }
.form-group label { display: block; margin-bottom: 5px; font-weight: 600; }
.form-group input { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; }
.form-group input.has-error { border-color: #e3342f; }
.error { color: #e3342f; font-size: 14px; margin-top: 5px; }
.form-actions { margin-top: 20px; display: flex; align-items: center; gap: 10px; }
.btn { padding: 8px 16px; background: #3490dc; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 14px; }
.btn:hover:not(:disabled) { background: #2779bd; }
.btn:disabled { opacity: 0.5; cursor: not-allowed; }
.cancel { color: #3490dc; text-decoration: none; }
.cancel:hover { text-decoration: underline; }
</style>
