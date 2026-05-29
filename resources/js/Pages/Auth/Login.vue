<template>
    <GuestLayout>
        <div class="card">
            <h1>Sign In</h1>

            <form @submit.prevent="submit">
                <div class="form-group">
                    <label>Email</label>
                    <input v-model="form.email" type="email" required autofocus :class="{ error: form.errors.email }">
                    <div v-if="form.errors.email" class="error-msg">{{ form.errors.email }}</div>
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input v-model="form.password" type="password" required :class="{ error: form.errors.password }">
                    <div v-if="form.errors.password" class="error-msg">{{ form.errors.password }}</div>
                </div>

                <div class="form-group checkbox">
                    <label><input v-model="form.remember" type="checkbox"> Remember me</label>
                </div>

                <button type="submit" class="btn" :disabled="form.processing">
                    {{ form.processing ? 'Signing in...' : 'Sign In' }}
                </button>

                <p class="link-text">
                    Don't have an account? <Link href="/register">Register</Link>
                </p>
            </form>
        </div>
    </GuestLayout>
</template>

<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';

const form = useForm({ email: '', password: '', remember: false });
const submit = () => form.post('/login');
</script>

<style scoped>
.card {
    background: white;
    padding: 40px;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0,0,0,.1);
    width: 400px;
    max-width: 90vw;
}
h1 { margin: 0 0 24px; font-size: 24px; text-align: center; }
.form-group { margin-bottom: 16px; }
.form-group label { display: block; margin-bottom: 4px; font-weight: 600; font-size: 14px; }
.form-group input[type="email"],
.form-group input[type="password"] { width: 100%; padding: 8px 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; box-sizing: border-box; }
.form-group input.error { border-color: #e3342f; }
.checkbox label { font-weight: 400; display: flex; align-items: center; gap: 8px; }
.checkbox input { width: auto; }
.error-msg { color: #e3342f; font-size: 13px; margin-top: 4px; }
.btn { width: 100%; padding: 10px; background: #3490dc; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; margin-top: 8px; }
.btn:hover:not(:disabled) { background: #2779bd; }
.btn:disabled { opacity: .5; cursor: not-allowed; }
.link-text { text-align: center; margin-top: 16px; font-size: 14px; color: #6b7280; }
.link-text a { color: #3490dc; text-decoration: none; }
.link-text a:hover { text-decoration: underline; }
</style>
