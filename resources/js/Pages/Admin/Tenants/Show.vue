<template>
    <AdminLayout>
        <div class="header">
            <h1>{{ tenant.name }}</h1>
            <div class="header-actions">
                <Link :href="`/admin/tenants/${tenant.id}/edit`" class="btn">Edit</Link>
                <button @click="destroy" class="btn btn-danger">Delete</button>
                <Link href="/admin/tenants" class="back-link">Back to List</Link>
            </div>
        </div>

        <div class="details-card">
            <h3>Tenant Details</h3>
            <div class="details-grid">
                <strong>Name:</strong> <span>{{ tenant.name }}</span>
                <strong>Email:</strong> <span>{{ tenant.email }}</span>
                <strong>Phone:</strong> <span>{{ tenant.phone || '—' }}</span>
                <strong>Created:</strong> <span>{{ formatDate(tenant.created_at) }}</span>
            </div>
        </div>

        <div class="bookings-section" v-if="tenant.bookings && tenant.bookings.length > 0">
            <h3>Bookings ({{ tenant.bookings.length }})</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Property</th>
                        <th>Check In</th>
                        <th>Check Out</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="booking in tenant.bookings" :key="booking.id">
                        <td>#{{ booking.id }}</td>
                        <td>{{ booking.property?.name || '—' }}</td>
                        <td>{{ formatDate(booking.check_in) }}</td>
                        <td>{{ formatDate(booking.check_out) }}</td>
                        <td>{{ booking.status }}</td>
                        <td><Link :href="`/admin/bookings/${booking.id}`" class="link">View</Link></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <EmptyState v-else title="No bookings yet" description="This tenant has no bookings yet." />
    </AdminLayout>
</template>

<script setup>
import EmptyState from '@/Components/EmptyState.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link, router } from '@inertiajs/vue3';

const props = defineProps({ tenant: Object });

const formatDate = (dateStr) => {
    if (!dateStr) return '—';
    return new Date(dateStr).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
};

const destroy = () => {
    if (!confirm('Are you sure you want to delete this tenant?')) return;
    router.delete(`/admin/tenants/${props.tenant.id}`);
};
</script>

<style scoped>
.header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
h1 { margin: 0; }
.header-actions { display: flex; gap: 10px; align-items: center; }
.back-link { color: #3490dc; text-decoration: none; }
.back-link:hover { text-decoration: underline; }
.btn { padding: 8px 16px; background: #3490dc; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 14px; text-decoration: none; display: inline-block; }
.btn:hover:not(:disabled) { background: #2779bd; }
.btn-danger { background: #e3342f; }
.btn-danger:hover:not(:disabled) { background: #cc1f1a; }
.details-card { background: #f7fafc; padding: 20px; border-radius: 8px; margin-bottom: 25px; }
.details-card h3 { margin-top: 0; margin-bottom: 15px; }
.details-grid { display: grid; grid-template-columns: 120px 1fr; gap: 10px; align-items: start; }
.bookings-section h3 { margin-bottom: 10px; }
.table { width: 100%; border-collapse: collapse; }
.table th, .table td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
.table th { background: #f7fafc; font-weight: 600; }
.link { color: #3490dc; text-decoration: none; }
.link:hover { text-decoration: underline; }
</style>
