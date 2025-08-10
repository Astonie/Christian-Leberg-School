import { Button } from "@/components/ui/button";

export function Pagination({ page, pageCount, onPageChange }: { page: number; pageCount: number; onPageChange: (p: number) => void }) {
  const prev = () => onPageChange(Math.max(1, page - 1));
  const next = () => onPageChange(Math.min(pageCount, page + 1));
  return (
    <div className="flex items-center justify-end gap-2 py-2">
      <Button variant="outline" size="sm" onClick={prev} disabled={page === 1}>Previous</Button>
      <div className="text-xs text-muted-foreground">Page {page} of {pageCount}</div>
      <Button variant="outline" size="sm" onClick={next} disabled={page === pageCount}>Next</Button>
    </div>
  );
}


