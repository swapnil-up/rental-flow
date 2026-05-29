<template>
    <TenantLayout>
        <div class="header">
            <h1>My Maintenance Requests</h1>
            <Link href="/maintenance/create" class="btn">New Request</Link>
        </div>

        <div v-if="requests.length === 0" class="empty-state">
            <p>No maintenance requests. <Link href="/maintenance/create">Submit one!</Link></p>
        </div>

        <table v-else class="table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Property</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="r in requests" :key="r.id">
                    <td>{{ r.title }}</td>
                    <td>{{ r.property_name }}</td>
                    <td><span class="priority" :class="r.priority">{{ capitalize(r.priority) }}</span></td>
                    <td>
                        <span class="badge" :style="{ backgroundColor: getColor(r.status_color) }">
                            {{ r.status_label }}
                        </span>
                    </td>
                    <td>{{ r.created_at }}</td>
                </tr>
            </tbody>
        </table>
    </TenantLayout>
</template>

<script setup>
import TenantLayout from '@/Layouts/TenantLayout.vue';
import { Link } from '@inertiajs/vue3';

defineProps({ requests: Array });

const capitalize = (s) => s.charAt(0).toUpperCase() + s.slice(1);
const getColor = (c) => ({ red: '#ef4444', orange: '#f59e0b', blue: '#3b82f6', green: '#10b981' }[c] || '#6b7280');
</script>

<style scoped>
.header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
h1 { margin: 0; font-size: 22px; }
.btn { padding: 8px 16px; background: #3490dc; color: white; text-decoration: none; border-radius: 4px; font-size: 14px; }
.btn:hover { background: #2779bd; }
.empty-state { padding: 40px; text-align: center; background: #f7fafc; border-radius: 8px; }
.table { width: 100%; border-collapse: collapse; }
.table th, .table td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
.table th { background: #f7fafc; font-weight: 600; }
.badge { display: inline-block; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; color: white; }
.priority { font-size: 12px; font-weight: 600; padding: 2px 8px; border-radius: 4px; }
.priority.low { background: #d1fae5; color: #065f46; }
.priority.medium { background: #fef3c7; color: #92400e; }
.priority.high { background: #fee2e2; color: #991b1b; }
.priority.emergency { background: #fce4ec; color: #b71c1c; font-weight: 700; }
</style>
