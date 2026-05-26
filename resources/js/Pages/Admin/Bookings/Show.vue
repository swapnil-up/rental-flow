<template>
    <AdminLayout>
        <div class="header">
            <h1>Booking #{{ booking.id }}</h1>
            <div class="header-actions">
                <button @click="destroy" class="btn btn-danger">Delete</button>
                <Link href="/admin/bookings" class="back-link">Back to List</Link>
            </div>
        </div>

        <div class="details-card">
            <h3>Booking Details</h3>

            <div class="details-grid">
                <div class="detail-row">
                    <strong>Property:</strong>
                    <span>{{ booking.property_name }}</span>
                </div>
                <div class="detail-row">
                    <strong>Check-In:</strong>
                    <span>{{ booking.check_in }}</span>
                </div>
                <div class="detail-row">
                    <strong>Check-Out:</strong>
                    <span>{{ booking.check_out }}</span>
                </div>
                <div class="detail-row">
                    <strong>Status:</strong>
                    <span>
                        <span class="badge" :style="{ backgroundColor: getBadgeColor(booking.status_color) }">
                            {{ capitalize(booking.status) }}
                        </span>
                    </span>
                </div>
            </div>

            <div class="actions-section" v-if="booking.available_actions.length > 0">
                <h4>Actions</h4>
                <div class="action-buttons">
                    <button
                        v-if="booking.available_actions.includes('confirm')"
                        @click="confirmBooking(booking.id)"
                        class="btn"
                        :disabled="processing"
                    >
                        Confirm
                    </button>
                    <button
                        v-if="booking.available_actions.includes('cancel')"
                        @click="cancelBooking(booking.id)"
                        class="btn btn-danger"
                        :disabled="processing"
                    >
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    booking: Object,
});

const processing = ref(false);

const capitalize = (str) => str.charAt(0).toUpperCase() + str.slice(1);

const getBadgeColor = (color) => {
    const colors = { orange: '#f59e0b', blue: '#3b82f6', green: '#10b981', gray: '#6b7280', red: '#ef4444' };
    return colors[color] || '#6b7280';
};

const destroy = () => {
    if (!confirm('Are you sure you want to delete this booking?')) return;
    router.delete(`/admin/bookings/${props.booking.id}`);
};

const confirmBooking = (bookingId) => {
    if (!confirm('Are you sure you want to confirm this booking?')) return;
    processing.value = true;
    router.post(`/admin/bookings/${bookingId}/confirm`, {}, { onFinish: () => { processing.value = false; } });
};

const cancelBooking = (bookingId) => {
    if (!confirm('Are you sure you want to cancel this booking?')) return;
    processing.value = true;
    router.post(`/admin/bookings/${bookingId}/cancel`, {}, { onFinish: () => { processing.value = false; } });
};
</script>

<style scoped>
.header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
h1 { margin: 0; }
.header-actions { display: flex; gap: 10px; align-items: center; }
.back-link { color: #3490dc; text-decoration: none; }
.back-link:hover { text-decoration: underline; }
.btn { padding: 8px 16px; background: #3490dc; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 14px; display: inline-block; text-decoration: none; }
.btn:hover:not(:disabled) { background: #2779bd; }
.btn:disabled { opacity: 0.5; cursor: not-allowed; }
.btn-danger { background: #e3342f; }
.btn-danger:hover:not(:disabled) { background: #cc1f1a; }
.details-card { background: #f7fafc; padding: 20px; border-radius: 8px; }
.details-card h3 { margin-top: 0; margin-bottom: 15px; }
.details-grid { display: grid; grid-template-columns: 200px 1fr; gap: 10px; }
.detail-row { display: contents; }
.detail-row strong { font-weight: 600; }
.badge { display: inline-block; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; color: white; }
.actions-section { margin-top: 25px; padding-top: 20px; border-top: 1px solid #ddd; }
.actions-section h4 { margin: 0 0 10px 0; }
.action-buttons { display: flex; gap: 10px; }
</style>
