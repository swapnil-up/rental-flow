<template>
    <AdminLayout>
        <div class="header">
            <h1>{{ property.name }}</h1>
            <div class="header-actions">
                <Link :href="`/admin/properties/${property.id}/edit`" class="btn">Edit</Link>
                <button @click="destroy" class="btn btn-danger">Delete</button>
                <Link href="/admin/properties" class="back-link">Back to List</Link>
            </div>
        </div>

        <div class="details-card">
            <h3>Property Details</h3>

            <div class="details-grid">
                <strong>Status:</strong>
                <span>
                    <span class="badge" :style="{ backgroundColor: getStatusColor(property.status) }">
                        {{ capitalize(property.status) }}
                    </span>
                </span>
                <div class="detail-row">
                    <strong>Type:</strong>
                    <span>{{ capitalize(property.type) }}</span>
                </div>

                <div class="detail-row">
                    <strong>Address:</strong>
                    <span>
                        {{ property.address }},
                        {{ property.city }},
                        {{ property.state }}
                        {{ property.zip_code }}
                    </span>
                </div>

                <div class="detail-row">
                    <strong>Bedrooms:</strong>
                    <span>{{ property.bedrooms }}</span>
                </div>

                <div class="detail-row">
                    <strong>Bathrooms:</strong>
                    <span>{{ property.bathrooms }}</span>
                </div>

                <div class="detail-row">
                    <strong>Square Feet:</strong>
                    <span>{{ formatNumber(property.square_feet) }} sq ft</span>
                </div>

                <div class="detail-row">
                    <strong>Monthly Rent:</strong>
                    <span>${{ formatMoney(property.monthly_rent) }}</span>
                </div>

                <div class="detail-row">
                    <strong>Utilities Cost:</strong>
                    <span>${{ formatMoney(property.utilities_cost) }}/month</span>
                </div>

                <div class="detail-row">
                    <strong>Management Fee:</strong>
                    <span>${{ formatMoney(property.management_fee) }}/month</span>
                </div>

                <div class="detail-row">
                    <strong>Total Monthly Cost:</strong>
                    <span class="highlight">${{ formatMoney(property.total_monthly_cost) }}/month</span>
                </div>

                <div class="detail-row">
                    <strong>Status:</strong>
                    <span>{{ capitalize(property.status) }}</span>
                </div>

                <div class="detail-row">
                    <strong>Created:</strong>
                    <span>{{ formatDate(property.created_at) }}</span>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link, router } from '@inertiajs/vue3';

const props = defineProps({
    property: Object,
});

const capitalize = (str) => str.charAt(0).toUpperCase() + str.slice(1);
const formatMoney = (cents) => (cents / 100).toFixed(2);
const formatNumber = (num) => num.toLocaleString();
const formatDate = (dateStr) => {
    const date = new Date(dateStr);
    return date.toLocaleDateString('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric'
    });
};

const destroy = () => {
    if (!confirm('Are you sure you want to delete this property?')) return;
    router.delete(`/admin/properties/${props.property.id}`);
};

const getStatusColor = (status) => {
    const colors = {
        available: '#10b981',
        occupied: '#3b82f6',
        maintenance: '#f59e0b',
        unlisted: '#6b7280',
    };
    return colors[status] || '#6b7280';
};
</script>

<style scoped>
.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

h1 {
    margin: 0;
}

.header-actions {
    display: flex;
    gap: 10px;
    align-items: center;
}

.btn {
    padding: 8px 16px;
    background: #3490dc;
    color: white;
    text-decoration: none;
    border-radius: 4px;
    display: inline-block;
    border: none;
    cursor: pointer;
    font-size: 14px;
}

.btn:hover {
    background: #2779bd;
}

.btn-danger {
    background: #e3342f;
}

.btn-danger:hover {
    background: #cc1f1a;
}

.back-link {
    color: #3490dc;
    text-decoration: none;
}

.back-link:hover {
    text-decoration: underline;
}

.details-card {
    background: #f7fafc;
    padding: 20px;
    border-radius: 8px;
}

.details-card h3 {
    margin-top: 0;
    margin-bottom: 15px;
}

.details-grid {
    display: grid;
    grid-template-columns: 200px 1fr;
    gap: 10px;
    align-items: start;
}

.detail-row {
    display: contents;
}

.detail-row strong {
    font-weight: 600;
}

.highlight {
    font-weight: 600;
    color: #2779bd;
}

.badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 600;
    color: white;
}
</style>