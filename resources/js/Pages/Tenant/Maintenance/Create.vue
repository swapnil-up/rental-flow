<template>
    <TenantLayout>
        <div class="header">
            <h1>Submit Maintenance Request</h1>
            <Link href="/maintenance" class="back-link">Back</Link>
        </div>

        <form @submit.prevent="submit" class="form">
            <div class="form-group">
                <label>Property</label>
                <select v-model="form.property_id" required :class="{ error: form.errors.property_id }">
                    <option value="">Select property...</option>
                    <option v-for="p in properties" :key="p.id" :value="p.id">{{ p.name }} — {{ p.city }}, {{ p.state }}</option>
                </select>
                <div v-if="form.errors.property_id" class="error-msg">{{ form.errors.property_id }}</div>
            </div>

            <div class="form-group">
                <label>Title</label>
                <input v-model="form.title" type="text" required :class="{ error: form.errors.title }">
                <div v-if="form.errors.title" class="error-msg">{{ form.errors.title }}</div>
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea v-model="form.description" rows="4" required :class="{ error: form.errors.description }"></textarea>
                <div v-if="form.errors.description" class="error-msg">{{ form.errors.description }}</div>
            </div>

            <div class="form-group">
                <label>Priority</label>
                <select v-model="form.priority" required :class="{ error: form.errors.priority }">
                    <option value="">Select priority...</option>
                    <option value="low">Low</option>
                    <option value="medium">Medium</option>
                    <option value="high">High</option>
                    <option value="emergency">Emergency</option>
                </select>
                <div v-if="form.errors.priority" class="error-msg">{{ form.errors.priority }}</div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn" :disabled="form.processing">
                    {{ form.processing ? 'Submitting...' : 'Submit Request' }}
                </button>
                <Link href="/maintenance" class="cancel">Cancel</Link>
            </div>
        </form>
    </TenantLayout>
</template>

<script setup>
import TenantLayout from '@/Layouts/TenantLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';

defineProps({ properties: Array });

const form = useForm({ property_id: '', title: '', description: '', priority: '' });
const submit = () => form.post('/maintenance');
</script>

<style scoped>
.header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
h1 { margin: 0; font-size: 22px; }
.back-link { color: #3490dc; text-decoration: none; }
.back-link:hover { text-decoration: underline; }
.form { max-width: 600px; }
.form-group { margin-bottom: 16px; }
.form-group label { display: block; margin-bottom: 4px; font-weight: 600; font-size: 14px; }
.form-group input, .form-group select, .form-group textarea { width: 100%; padding: 8px 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; box-sizing: border-box; font-family: inherit; }
.form-group input.error, .form-group select.error, .form-group textarea.error { border-color: #e3342f; }
.error-msg { color: #e3342f; font-size: 13px; margin-top: 4px; }
.form-actions { margin-top: 20px; display: flex; gap: 10px; align-items: center; }
.btn { padding: 10px 20px; background: #3490dc; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 14px; }
.btn:hover:not(:disabled) { background: #2779bd; }
.btn:disabled { opacity: .5; cursor: not-allowed; }
.cancel { color: #3490dc; text-decoration: none; }
.cancel:hover { text-decoration: underline; }
</style>
