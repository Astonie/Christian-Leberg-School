"use client";

import { PageHeader } from "@/components/ui/page-header";
import { DataTable } from "@/components/data/DataTable";
import { ColumnDef } from "@tanstack/react-table";
import { useLoanProducts, useDeleteLoanProduct } from "@/hooks/useLoanProducts";
import { Button } from "@/components/ui/button";

type Row = { id: string; name: string; interestRateAnnualPct: number; maxAmount: number; term: string };
const columns: ColumnDef<Row>[] = [
  { accessorKey: "name", header: "Name" },
  { accessorKey: "interestRateAnnualPct", header: "Rate (p.a. %)" },
  { accessorKey: "maxAmount", header: "Max Amount", cell: ({ row }) => new Intl.NumberFormat("en-UG", { style: "currency", currency: "UGX", maximumFractionDigits: 0 }).format(row.original.maxAmount) },
  { accessorKey: "term", header: "Term" },
];

export default function LoanProductsPage() {
  const { data = [], isLoading } = useLoanProducts();
  const del = useDeleteLoanProduct();
  const rows: Row[] = data.map((p) => ({ id: p.id, name: p.name, interestRateAnnualPct: p.interestRateAnnualPct, maxAmount: p.maxAmount, term: `${p.minTermMonths}-${p.maxTermMonths} mo` }));
  return (
    <div className="space-y-4">
      <PageHeader title="Loan Products" subtitle="Define loan terms" actions={<Button asChild><a href="/loan-products/new">Create</a></Button>} />
      <DataTable
        columns={[
          ...columns,
          {
            id: "actions",
            header: "Actions",
            cell: ({ row }) => (
              <div className="flex gap-2 text-sm">
                <a href={`/loan-products/${row.original.id}/edit`} className="text-primary">Edit</a>
                <button className="text-destructive" onClick={() => del.mutate(row.original.id)}>Delete</button>
              </div>
            ),
          },
        ]}
        data={rows}
        isLoading={isLoading}
        enableColumnFilters
      />
    </div>
  );
}


