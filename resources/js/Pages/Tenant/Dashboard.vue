<template>
    <TenantLayout>
        <h1>My Bookings</h1>

        <div v-if="bookings.length === 0" class="empty-state">
            <p>You don't have any bookings yet.</p>
        </div>

        <table v-else class="table">
            <thead>
                <tr>
                    <th>Property</th>
                    <th>Check In</th>
                    <th>Check Out</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="booking in bookings" :key="booking.id">
                    <td>{{ booking.property_name }}</td>
                    <td>{{ booking.check_in }}</td>
                    <td>{{ booking.check_out }}</td>
                    <td>
                        <span class="badge" :style="{ backgroundColor: getBadgeColor(booking.status_color) }">
                            {{ capitalize(booking.status) }}
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>
    </TenantLayout>
</template>

<script setup>
import TenantLayout from '@/Layouts/TenantLayout.vue';

defineProps({
    bookings: Array,
});

const capitalize = (str) => str.charAt(0).toUpperCase() + str.slice(1);

const getBadgeColor = (color) => {
    const colors = { orange: '#f59e0b', blue: '#3b82f6', green: '#10b981', gray: '#6b7280', red: '#ef4444' };
    return colors[color] || '#6b7280';
};
</script>

<style scoped>
h1 { margin: 0 0 20px; }
.empty-state { padding: 40px; text-align: center; background: #f7fafc; border-radius: 8px; }
.table { width: 100%; border-collapse: collapse; }
.table th, .table td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
.table th { background: #f7fafc; font-weight: 600; }
.badge { display: inline-block; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; color: white; }
</style>
