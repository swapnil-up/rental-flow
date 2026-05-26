<template>
    <div class="admin-layout">
        <nav class="nav">
            <Link href="/admin/properties">Properties</Link>
            <Link href="/admin/bookings">Bookings</Link>
            <Link href="/admin/tenants">Tenants</Link>
        </nav>

        <div v-if="$page.props.flash.success" class="success">
            {{ $page.props.flash.success }}
        </div>

        <div v-if="$page.props.flash.error" class="error">
            {{ $page.props.flash.error }}
        </div>

        <main>
            <slot />
        </main>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<style scoped>
.admin-layout {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
    font-family: system-ui, -apple-system, sans-serif;
}

.nav {
    margin-bottom: 30px;
    padding-bottom: 10px;
    border-bottom: 2px solid #ddd;
}

.nav a {
    margin-right: 15px;
    color: #3490dc;
    text-decoration: none;
}

.nav a:hover {
    text-decoration: underline;
}

.success {
    padding: 12px;
    background: #d4edda;
    color: #155724;
    border-radius: 4px;
    margin-bottom: 20px;
}

.error {
    padding: 12px;
    background: #f8d7da;
    color: #721c24;
    border-radius: 4px;
    margin-bottom: 20px;
}

main {
    min-height: 500px;
}
</style>