import re

with open('resources/js/pages/Kader/Keluarga/Show.vue', 'r') as f:
    content = f.read()

# Add isAdmin computed property if not exists
if 'const isAdmin =' not in content:
    content = content.replace(
        'const flash = computed(() => (page.props as any).flash);',
        "const flash = computed(() => (page.props as any).flash);\nconst isAdmin = computed(() => (page.props as any).auth?.user?.role === 'admin');"
    )

# Now find Data Khusus Warga PKK and wrap it in fieldset. It might appear twice (Edit Anggota, Tambah Anggota).
# We can search for the start of Data Khusus Warga PKK
pattern_start = r'<div class="col-span-1 md:col-span-2 mt-6 border-t border-gray-200 pt-6">.*?Data Khusus Warga PKK</span>.*?</div>'
# Actually, the classes might be different. Let's find exactly how it's written in Show.vue.
