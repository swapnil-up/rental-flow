<template>
    <AdminLayout>
        <h1>Edit Expense</h1>

        <form @submit.prevent="submit" class="form">
            <div class="field">
                <label>Property</label>
                <select v-model="form.property_id" required>
                    <option value="" disabled>Select property</option>
                    <option v-for="p in properties" :key="p.id" :value="p.id">{{ p.name }}</option>
                </select>
                <p v-if="errors.property_id" class="error">{{ errors.property_id }}</p>
            </div>

            <div class="field">
                <label>Category</label>
                <select v-model="form.category" required>
                    <option value="" disabled>Select category</option>
                    <option v-for="c in categories" :key="c.value" :value="c.value">{{ c.label }}</option>
                </select>
                <p v-if="errors.category" class="error">{{ errors.category }}</p>
            </div>

            <div class="field">
                <label>Amount ($)</label>
                <input v-model="form.amount" type="number" step="0.01" min="0" required />
                <p v-if="errors.amount" class="error">{{ errors.amount }}</p>
            </div>

            <div class="field">
                <label>Date</label>
                <input v-model="form.date" type="date" required />
                <p v-if="errors.date" class="error">{{ errors.date }}</p>
            </div>

            <div class="field">
                <label>Description</label>
                <textarea v-model="form.description" rows="3"></textarea>
                <p v-if="errors.description" class="error">{{ errors.description }}</p>
            </div>

            <div class="actions">
                <button type="submit" class="btn">Update</button>
                <Link href="/admin/expenses" class="btn-cancel">Cancel</Link>
            </div>
        </form>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link, router, useForm } from '@inertiajs/vue3';

const props = defineProps({ expense: Object, categories: Array, properties: Array });

const form = useForm({
    property_id: props.expense.property_id,
    category: props.expense.category,
    amount: props.expense.amount,
    date: props.expense.date,
    description: props.expense.description || '',
});

const errors = form.errors;

const submit = () => {
    router.put(`/admin/expenses/${props.expense.id}`, form);
};
</script>

<style scoped>
h1 { margin: 0 0 20px; }
.form { max-width: 500px; }
.field { margin-bottom: 16px; }
.field label { display: block; font-weight: 600; margin-bottom: 4px; font-size: 14px; }
.field input, .field select, .field textarea { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; }
.field textarea { resize: vertical; }
.error { color: #e3342f; font-size: 12px; margin: 4px 0 0; }
.actions { display: flex; gap: 10px; margin-top: 20px; }
.btn { padding: 10px 20px; background: #3490dc; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 14px; }
.btn:hover { background: #2779bd; }
.btn-cancel { padding: 10px 20px; background: #6b7280; color: white; text-decoration: none; border-radius: 4px; font-size: 14px; }
.btn-cancel:hover { background: #4b5563; }
</style>
