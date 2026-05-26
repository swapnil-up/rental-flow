<template>
    <AdminLayout>
        <div class="header">
            <h1>Create Booking</h1>
            <Link href="/admin/bookings" class="back-link">Back to List</Link>
        </div>

        <form @submit.prevent="submit" class="form">
            <div class="form-group">
                <label>Property</label>
                <select v-model="form.property_id" required :class="{ 'has-error': form.errors.property_id }">
                    <option value="">Select property...</option>
                    <option v-for="property in properties" :key="property.id" :value="property.id">
                        {{ property.name }} — {{ property.city }}, {{ property.state }}
                    </option>
                </select>
                <div v-if="form.errors.property_id" class="error">{{ form.errors.property_id }}</div>
            </div>

            <div class="form-group">
                <label>Tenant (optional)</label>
                <select v-model="form.tenant_id" :class="{ 'has-error': form.errors.tenant_id }">
                    <option value="">No tenant assigned</option>
                    <option v-for="tenant in tenants" :key="tenant.id" :value="tenant.id">
                        {{ tenant.name }}
                    </option>
                </select>
                <div v-if="form.errors.tenant_id" class="error">{{ form.errors.tenant_id }}</div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Check-In Date</label>
                    <input
                        v-model="form.check_in"
                        type="date"
                        required
                        :class="{ 'has-error': form.errors.check_in }"
                    >
                    <div v-if="form.errors.check_in" class="error">{{ form.errors.check_in }}</div>
                </div>

                <div class="form-group">
                    <label>Check-Out Date</label>
                    <input
                        v-model="form.check_out"
                        type="date"
                        required
                        :class="{ 'has-error': form.errors.check_out }"
                    >
                    <div v-if="form.errors.check_out" class="error">{{ form.errors.check_out }}</div>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn" :disabled="form.processing">
                    {{ form.processing ? 'Creating...' : 'Create Booking' }}
                </button>
                <Link href="/admin/bookings" class="cancel">Cancel</Link>
            </div>
        </form>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';

defineProps({
    properties: Array,
    tenants: Array,
});

const form = useForm({
    property_id: '',
    tenant_id: '',
    check_in: '',
    check_out: '',
});

const submit = () => {
    form.post('/admin/bookings');
};
</script>

<style scoped>
.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}
h1 { margin: 0; }
.back-link { color: #3490dc; text-decoration: none; }
.back-link:hover { text-decoration: underline; }
.form { max-width: 600px; }
.form-group { margin-bottom: 15px; }
.form-group label { display: block; margin-bottom: 5px; font-weight: 600; }
.form-group input,
.form-group select { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; }
.form-group input.has-error,
.form-group select.has-error { border-color: #e3342f; }
.form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
.error { color: #e3342f; font-size: 14px; margin-top: 5px; }
.form-actions { margin-top: 20px; display: flex; align-items: center; gap: 10px; }
.btn { padding: 8px 16px; background: #3490dc; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 14px; }
.btn:hover:not(:disabled) { background: #2779bd; }
.btn:disabled { opacity: 0.5; cursor: not-allowed; }
.cancel { color: #3490dc; text-decoration: none; }
.cancel:hover { text-decoration: underline; }
</style>
