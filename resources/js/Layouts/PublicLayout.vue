<template>
    <div class="public-layout">
        <header class="site-header">
            <Link href="/" class="logo">RentalFlow</Link>
            <nav>
                <Link href="/properties">Properties</Link>
            </nav>
        </header>
        <div v-if="$page.props.flash.success" class="flash-success">{{ $page.props.flash.success }}</div>
        <main><slot /></main>
        <footer class="site-footer">
            &copy; {{ new Date().getFullYear() }} RentalFlow. All rights reserved.
        </footer>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<style>
body { margin: 0; font-family: system-ui, -apple-system, sans-serif; background: #f9fafb; color: #1f2937; }
.public-layout { min-height: 100vh; display: flex; flex-direction: column; }
.site-header { background: white; border-bottom: 1px solid #e5e7eb; padding: 12px 24px; display: flex; justify-content: space-between; align-items: center; }
.logo { font-size: 20px; font-weight: 700; color: #3490dc; text-decoration: none; }
.site-header nav { display: flex; gap: 20px; }
.site-header nav a { color: #374151; text-decoration: none; font-size: 14px; }
.site-header nav a:hover { color: #3490dc; }
main { flex: 1; max-width: 1100px; margin: 0 auto; padding: 30px 20px; width: 100%; }
.flash-success { max-width: 1100px; margin: 0 auto; padding: 12px 20px; background: #d4edda; color: #155724; font-size: 14px; }
.site-footer { text-align: center; padding: 20px; font-size: 12px; color: #9ca3af; border-top: 1px solid #e5e7eb; }
</style>
