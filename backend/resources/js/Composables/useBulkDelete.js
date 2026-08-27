import { ref, watch } from "vue";
import Swal from "sweetalert2";
import axios from "axios";

export function useBulkDelete(localData, routeName) {
    const selectedIds = ref([]);
    const selectAll = ref(false);

    watch(selectAll, (val) => {
        if (localData.value?.data) {
            selectedIds.value = val ? localData.value.data.map(s => s.id) : [];
        }
    });

    watch(selectedIds, (val) => {
        const ids = localData.value?.data?.map(s => s.id) || [];
        selectAll.value = ids.length > 0 && val.length === ids.length;
    }, { deep: true });

    const deleteSingle = (id, deleteRoute, onSuccess) => {
        Swal.fire({
            title: "Apakah Anda yakin?",
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Ya, hapus!",
            cancelButtonText: "Batal",
        }).then((result) => {
            if (result.isConfirmed && window.route) {
                const url = window.route(deleteRoute, id);
                axios.delete(url).then(() => {
                    localData.value = {
                        ...localData.value,
                        data: localData.value.data.filter(s => s.id !== id),
                        total: localData.value.total - 1,
                    };
                    selectedIds.value = selectedIds.value.filter(sid => sid !== id);
                    if (onSuccess) onSuccess();
                }).catch(() => {
                    Swal.fire({ icon: "error", title: "Gagal!", text: "Terjadi kesalahan." });
                });
            }
        });
    };

    const bulkDelete = () => {
        if (selectedIds.value.length === 0) return;
        Swal.fire({
            title: `Hapus ${selectedIds.value.length} data?`,
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Ya, hapus semua!",
            cancelButtonText: "Batal",
        }).then((result) => {
            if (result.isConfirmed) {
                axios.delete(window.route(routeName), {
                    data: { ids: selectedIds.value },
                }).then(() => {
                    Swal.fire({ icon: "success", title: "Berhasil!", text: `${selectedIds.value.length} data dihapus.`, timer: 1500, showConfirmButton: false });
                    localData.value = {
                        ...localData.value,
                        data: localData.value.data.filter(s => !selectedIds.value.includes(s.id)),
                        total: localData.value.total - selectedIds.value.length,
                    };
                    selectedIds.value = [];
                }).catch(() => {
                    Swal.fire({ icon: "error", title: "Gagal!", text: "Terjadi kesalahan." });
                });
            }
        });
    };

    return { selectedIds, selectAll, deleteSingle, bulkDelete };
}
