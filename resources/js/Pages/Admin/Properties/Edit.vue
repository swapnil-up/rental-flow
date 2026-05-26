<template>
    <AdminLayout>
        <div class="header">
            <h1>Edit Property</h1>
            <Link href="/admin/properties" class="back-link">Back to List</Link>
        </div>

        <form @submit.prevent="submit" class="form">
            <div class="form-group">
                <label>Property Name</label>
                <input
                    v-model="form.name"
                    type="text"
                    required
                    :class="{ 'has-error': form.errors.name }"
                >
                <div v-if="form.errors.name" class="error">{{ form.errors.name }}</div>
            </div>

            <div class="form-group">
                <label>Type</label>
                <select v-model="form.type" required :class="{ 'has-error': form.errors.type }">
                    <option value="">Select type...</option>
                    <option value="apartment">Apartment</option>
                    <option value="house">House</option>
                    <option value="condo">Condo</option>
                    <option value="studio">Studio</option>
                </select>
                <div v-if="form.errors.type" class="error">{{ form.errors.type }}</div>
            </div>

            <div class="form-group">
                <label>Address</label>
                <input v-model="form.address" type="text" required :class="{ 'has-error': form.errors.address }">
                <div v-if="form.errors.address" class="error">{{ form.errors.address }}</div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>City</label>
                    <input v-model="form.city" type="text" required :class="{ 'has-error': form.errors.city }">
                    <div v-if="form.errors.city" class="error">{{ form.errors.city }}</div>
                </div>
                <div class="form-group">
                    <label>State</label>
                    <input v-model="form.state" type="text" maxlength="2" required :class="{ 'has-error': form.errors.state }">
                    <div v-if="form.errors.state" class="error">{{ form.errors.state }}</div>
                </div>
                <div class="form-group">
                    <label>Zip Code</label>
                    <input v-model="form.zip_code" type="text" required :class="{ 'has-error': form.errors.zip_code }">
                    <div v-if="form.errors.zip_code" class="error">{{ form.errors.zip_code }}</div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Bedrooms</label>
                    <input v-model.number="form.bedrooms" type="number" min="0" required :class="{ 'has-error': form.errors.bedrooms }">
                    <div v-if="form.errors.bedrooms" class="error">{{ form.errors.bedrooms }}</div>
                </div>
                <div class="form-group">
                    <label>Bathrooms</label>
                    <input v-model.number="form.bathrooms" type="number" step="0.5" min="0" required :class="{ 'has-error': form.errors.bathrooms }">
                    <div v-if="form.errors.bathrooms" class="error">{{ form.errors.bathrooms }}</div>
                </div>
                <div class="form-group">
                    <label>Square Feet</label>
                    <input v-model.number="form.square_feet" type="number" min="1" required :class="{ 'has-error': form.errors.square_feet }">
                    <div v-if="form.errors.square_feet" class="error">{{ form.errors.square_feet }}</div>
                </div>
            </div>

            <div class="form-group">
                <label>Monthly Rent ($)</label>
                <input v-model.number="form.monthly_rent" type="number" step="0.01" min="0" required :class="{ 'has-error': form.errors.monthly_rent }">
                <div v-if="form.errors.monthly_rent" class="error">{{ form.errors.monthly_rent }}</div>
            </div>

            <div class="form-group">
                <label>Utilities Cost ($/month)</label>
                <input v-model.number="form.utilities_cost" type="number" step="0.01" min="0" :class="{ 'has-error': form.errors.utilities_cost }">
                <div v-if="form.errors.utilities_cost" class="error">{{ form.errors.utilities_cost }}</div>
            </div>

            <div class="form-group">
                <label>Management Fee ($/month)</label>
                <input v-model.number="form.management_fee" type="number" step="0.01" min="0" :class="{ 'has-error': form.errors.management_fee }">
                <div v-if="form.errors.management_fee" class="error">{{ form.errors.management_fee }}</div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn" :disabled="form.processing">
                    {{ form.processing ? 'Saving...' : 'Save Changes' }}
                </button>
                <Link href="/admin/properties" class="cancel">Cancel</Link>
            </div>
        </form>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    property: Object,
});

const form = useForm({
    name: props.property.name,
    type: props.property.type,
    address: props.property.address,
    city: props.property.city,
    state: props.property.state,
    zip_code: props.property.zip_code,
    bedrooms: props.property.bedrooms,
    bathrooms: props.property.bathrooms,
    square_feet: props.property.square_feet,
    monthly_rent: (props.property.monthly_rent / 100).toFixed(2),
    utilities_cost: (props.property.utilities_cost / 100).toFixed(2),
    management_fee: (props.property.management_fee / 100).toFixed(2),
});

const submit = () => {
    form.put(`/admin/properties/${props.property.id}`);
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
.form { max-width: 800px; }
.form-group { margin-bottom: 15px; }
.form-group label { display: block; margin-bottom: 5px; font-weight: 600; }
.form-group input,
.form-group select { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; }
.form-group input.has-error,
.form-group select.has-error { border-color: #e3342f; }
.form-row { display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; }
.error { color: #e3342f; font-size: 14px; margin-top: 5px; }
.form-actions { margin-top: 20px; display: flex; align-items: center; gap: 10px; }
.btn { padding: 8px 16px; background: #3490dc; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 14px; }
.btn:hover:not(:disabled) { background: #2779bd; }
.btn:disabled { opacity: 0.5; cursor: not-allowed; }
.cancel { color: #3490dc; text-decoration: none; }
.cancel:hover { text-decoration: underline; }
</style>
