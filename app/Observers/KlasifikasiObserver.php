<?php

namespace App\Observers;

use App\Models\Klasifikasi;
use Illuminate\Support\Facades\Log;

class KlasifikasiObserver
{
    /**
     * Handle the Klasifikasi "created" event.
     * Setelah record baru dibuat, cek apakah parent perlu diupdate is_leaf = false.
     */
    public function created(Klasifikasi $klasifikasi): void
    {
        $this->updateParentLeafStatus($klasifikasi->parent_kode);
    }

    /**
     * Handle the Klasifikasi "updated" event.
     * Jika parent_kode berubah, perlu update status leaf di parent lama dan parent baru.
     */
    public function updated(Klasifikasi $klasifikasi): void
    {
        // Cek apakah parent_kode berubah
        if ($klasifikasi->isDirty('parent_kode')) {
            $oldParent = $klasifikasi->getOriginal('parent_kode');
            $newParent = $klasifikasi->parent_kode;

            // Update parent lama (mungkin sekarang jadi leaf jika tidak punya anak lain)
            $this->updateParentLeafStatus($oldParent);
            // Update parent baru (sekarang pasti bukan leaf)
            $this->updateParentLeafStatus($newParent);
        }

        // Cek apakah kode berubah (mempengaruhi anak-anaknya)
        if ($klasifikasi->isDirty('kode')) {
            // Update is_leaf record ini berdasarkan apakah punya anak
            $this->updateNodeLeafStatus($klasifikasi->kode);
        }
    }

    /**
     * Handle the Klasifikasi "deleted" event.
     * Setelah record dihapus, cek apakah parent sekarang jadi leaf.
     */
    public function deleted(Klasifikasi $klasifikasi): void
    {
        $this->updateParentLeafStatus($klasifikasi->parent_kode);
    }

    /**
     * Update status is_leaf dari parent node.
     * Jika parent punya anak, set is_leaf = false. Jika tidak, set is_leaf = true.
     */
    protected function updateParentLeafStatus(?string $parentKode): void
    {
        if (!$parentKode) {
            return; // root tidak punya parent
        }

        $parent = Klasifikasi::where('kode', $parentKode)->first();
        if (!$parent) {
            return; // parent tidak ditemukan
        }

        $hasChildren = Klasifikasi::where('parent_kode', $parentKode)->exists();
        
        // Update is_leaf tanpa trigger observer lagi (gunakan withoutEvents atau direct query)
        $shouldBeLeaf = !$hasChildren;
        
        if ($parent->is_leaf !== $shouldBeLeaf) {
            $parent->timestamps = false; // jangan update updated_at
            $parent->is_leaf = $shouldBeLeaf;
            $parent->saveQuietly(); // save tanpa trigger event
            
            Log::info("Auto-updated is_leaf for parent '{$parentKode}' to " . ($shouldBeLeaf ? 'true' : 'false'));
        }
    }

    /**
     * Update status is_leaf dari node spesifik berdasarkan apakah punya anak.
     */
    protected function updateNodeLeafStatus(string $kode): void
    {
        $node = Klasifikasi::where('kode', $kode)->first();
        if (!$node) {
            return;
        }

        $hasChildren = Klasifikasi::where('parent_kode', $kode)->exists();
        $shouldBeLeaf = !$hasChildren;

        if ($node->is_leaf !== $shouldBeLeaf) {
            $node->timestamps = false;
            $node->is_leaf = $shouldBeLeaf;
            $node->saveQuietly();
            
            Log::info("Auto-updated is_leaf for node '{$kode}' to " . ($shouldBeLeaf ? 'true' : 'false'));
        }
    }
}
