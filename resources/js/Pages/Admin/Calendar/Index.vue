<template>
    <AdminLayout>
        <div class="calendar">
            <div class="calendar-header">
                <Link :href="`/admin/calendar?month=${prevMonth.month}&year=${prevMonth.year}`" class="nav-link">&larr; Prev</Link>
                <h1>{{ monthName }}</h1>
                <Link :href="`/admin/calendar?month=${nextMonth.month}&year=${nextMonth.year}`" class="nav-link">Next &rarr;</Link>
            </div>

            <div class="weekdays">
                <div v-for="d in weekdays" :key="d" class="weekday">{{ d }}</div>
            </div>

            <div class="days-grid">
                <div v-for="day in emptyCells" :key="'e' + day" class="day empty"></div>
                <div v-for="day in daysInMonth" :key="day"
                    class="day"
                    :class="{ today: isToday(day), weekend: isWeekend(day) }">
                    <span class="day-number">{{ day }}</span>
                    <div class="day-bookings">
                        <div v-for="b in bookingsForDay(day)" :key="b.id"
                            class="booking-dot"
                            :title="`${b.property_name} — ${b.tenant_name || '?'}`"
                            :style="{ backgroundColor: getColor(b.status_color) }">
                        </div>
                    </div>
                </div>
            </div>

            <div class="legend">
                <h3>Legend</h3>
                <div class="legend-items">
                    <div v-for="p in properties" :key="p.id" class="legend-item">
                        <span class="dot" :style="{ backgroundColor: propertyColor(p.id) }"></span>
                        {{ p.name }}
                    </div>
                </div>
            </div>

            <div v-if="bookings.length > 0" class="booking-list">
                <h3>Bookings This Month</h3>
                <table class="table">
                    <thead>
                        <tr><th>Property</th><th>Tenant</th><th>Check-In</th><th>Check-Out</th><th>Status</th><th></th></tr>
                    </thead>
                    <tbody>
                        <tr v-for="b in bookings" :key="b.id">
                            <td>
                                <span class="dot" :style="{ backgroundColor: propertyColor(b.property_id) }"></span>
                                {{ b.property_name }}
                            </td>
                            <td>{{ b.tenant_name || '—' }}</td>
                            <td>{{ b.check_in }}</td>
                            <td>{{ b.check_out }}</td>
                            <td>
                                <span class="badge" :style="{ backgroundColor: getColor(b.status_color) }">{{ capitalize(b.status) }}</span>
                            </td>
                            <td><Link :href="`/admin/bookings/${b.id}`" class="link">View</Link></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <EmptyState v-else title="No bookings this month" description="The calendar will show bookings once they are confirmed." />
        </div>
    </AdminLayout>
</template>

<script setup>
import { computed } from 'vue';
import EmptyState from '@/Components/EmptyState.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    month: Number,
    year: Number,
    monthName: String,
    startDayOfWeek: Number,
    daysInMonth: Number,
    bookings: Array,
    prevMonth: Object,
    nextMonth: Object,
});

const weekdays = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
const emptyCells = computed(() => props.startDayOfWeek);

const isToday = (day) => {
    const d = new Date();
    return d.getFullYear() === props.year && d.getMonth() + 1 === props.month && d.getDate() === day;
};

const isWeekend = (day) => {
    const dow = (props.startDayOfWeek + day - 1) % 7;
    return dow === 0 || dow === 6;
};

const bookingsForDay = (day) => {
    const dateStr = `${props.year}-${String(props.month).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
    return props.bookings.filter(b => dateStr >= b.check_in && dateStr <= b.check_out);
};

const properties = computed(() => {
    const map = {};
    props.bookings.forEach(b => {
        if (!map[b.property_id]) {
            map[b.property_id] = { id: b.property_id, name: b.property_name };
        }
    });
    return Object.values(map);
});

const propertyColors = [
    '#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6',
    '#ec4899', '#14b8a6', '#f97316', '#06b6d4', '#84cc16',
];

const propertyColor = (id) => {
    const idx = properties.value.findIndex(p => p.id === id);
    return propertyColors[idx % propertyColors.length];
};

const getColor = (c) => ({ orange: '#f59e0b', green: '#10b981', red: '#ef4444', gray: '#6b7280', blue: '#3b82f6' }[c] || '#6b7280');
const capitalize = (s) => s.charAt(0).toUpperCase() + s.slice(1);
</script>

<style scoped>
.calendar { max-width: 1000px; }
.calendar-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; }
.calendar-header h1 { margin: 0; font-size: 20px; }
.nav-link { color: #3490dc; text-decoration: none; font-size: 14px; }
.nav-link:hover { text-decoration: underline; }

.weekdays { display: grid; grid-template-columns: repeat(7, 1fr); gap: 2px; margin-bottom: 2px; }
.weekday { background: #f3f4f6; padding: 8px; text-align: center; font-weight: 600; font-size: 12px; }

.days-grid { display: grid; grid-template-columns: repeat(7, 1fr); gap: 2px; }
.day { background: white; border: 1px solid #e5e7eb; min-height: 80px; padding: 6px; position: relative; }
.day.empty { background: #f9fafb; border-color: transparent; }
.day.today { background: #eff6ff; border-color: #3b82f6; }
.day-number { font-size: 12px; font-weight: 600; color: #374151; }
.day-bookings { margin-top: 4px; display: flex; flex-wrap: wrap; gap: 3px; }
.booking-dot { width: 20px; height: 6px; border-radius: 3px; cursor: pointer; }

.legend { margin: 20px 0; }
.legend h3 { font-size: 14px; margin: 0 0 8px; }
.legend-items { display: flex; flex-wrap: wrap; gap: 12px; }
.legend-item { font-size: 12px; display: flex; align-items: center; gap: 4px; }
.dot { width: 10px; height: 10px; border-radius: 50%; display: inline-block; }

.booking-list { margin-top: 20px; }
h3 { font-size: 16px; margin: 0 0 10px; }
.table { width: 100%; border-collapse: collapse; font-size: 14px; }
.table th, .table td { padding: 10px 12px; text-align: left; border-bottom: 1px solid #e5e7eb; }
.table th { background: #f9fafb; font-weight: 600; }
.link { color: #3490dc; text-decoration: none; }
.link:hover { text-decoration: underline; }
.badge { display: inline-block; padding: 3px 10px; border-radius: 10px; font-size: 11px; font-weight: 600; color: white; }
</style>
