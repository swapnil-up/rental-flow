<template>
    <AdminLayout>
        <div class="header">
            <h1>Bookings</h1>
        </div>

        <div v-if="bookings.data.length === 0" class="empty-state">
            <p>No bookings yet.</p>
        </div>

        <table v-else class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Property</th>
                    <th>Check In</th>
                    <th>Check Out</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="booking in bookings.data" :key="booking.id">
                    <td>#{{ booking.id }}</td>
                    <td>{{ booking.property_name }}</td>
                    <td>{{ booking.check_in }}</td>
                    <td>{{ booking.check_out }}</td>
                    <td>
                        <span 
                            class="badge" 
                            :style="{ backgroundColor: getBadgeColor(booking.status_color) }"
                        >
                            {{ capitalize(booking.status) }}
                        </span>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <button
                                v-if="booking.available_actions.includes('confirm')"
                                @click="confirmBooking(booking.id)"
                                class="btn-small btn-blue"
                                :disabled="processing"
                            >
                                Confirm
                            </button>
                            
                            <button
                                v-if="booking.available_actions.includes('cancel')"
                                @click="cancelBooking(booking.id)"
                                class="btn-small btn-red"
                                :disabled="processing"
                            >
                                Cancel
                            </button>
                            
                            <span v-if="booking.available_actions.length === 0" class="text-muted">
                                No actions available
                            </span>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>

        <div v-if="bookings.links.length > 3" class="pagination">
            <Link
                v-for="link in bookings.links"
                :key="link.label"
                :href="link.url"
                :class="{ active: link.active, disabled: !link.url }"
                v-html="link.label"
            />
        </div>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({
    bookings: Object,
});

const processing = ref(false);

const capitalize = (str) => str.charAt(0).toUpperCase() + str.slice(1);

const getBadgeColor = (color) => {
    const colors = {
        orange: '#f59e0b',
        blue: '#3b82f6',
        green: '#10b981',
        gray: '#6b7280',
        red: '#ef4444',
    };
    return colors[color] || '#6b7280';
};

const confirmBooking = (bookingId) => {
    if (!confirm('Are you sure you want to confirm this booking?')) return;
    
    processing.value = true;
    router.post(
        `/admin/bookings/${bookingId}/confirm`,
        {},
        {
            onFinish: () => {
                processing.value = false;
            },
        }
    );
};

const cancelBooking = (bookingId) => {
    if (!confirm('Are you sure you want to cancel this booking?')) return;
    
    processing.value = true;
    router.post(
        `/admin/bookings/${bookingId}/cancel`,
        {},
        {
            onFinish: () => {
                processing.value = false;
            },
        }
    );
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

.empty-state {
    padding: 40px;
    text-align: center;
    background: #f7fafc;
    border-radius: 8px;
}

.table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

.table th,
.table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

.table th {
    background: #f7fafc;
    font-weight: 600;
}

.badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 600;
    color: white;
}

.action-buttons {
    display: flex;
    gap: 8px;
    align-items: center;
}

.btn-small {
    padding: 4px 12px;
    font-size: 12px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    color: white;
}

.btn-blue {
    background: #3b82f6;
}

.btn-blue:hover:not(:disabled) {
    background: #2563eb;
}

.btn-red {
    background: #ef4444;
}

.btn-red:hover:not(:disabled) {
    background: #dc2626;
}

.btn-small:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.text-muted {
    color: #6b7280;
    font-size: 12px;
}

.pagination {
    display: flex;
    gap: 5px;
    margin-top: 20px;
}

.pagination a {
    padding: 8px 12px;
    border: 1px solid #ddd;
    text-decoration: none;
    color: #3490dc;
    border-radius: 4px;
}

.pagination a.active {
    background: #3490dc;
    color: white;
    border-color: #3490dc;
}

.pagination a.disabled {
    opacity: 0.5;
    pointer-events: none;
}
</style>