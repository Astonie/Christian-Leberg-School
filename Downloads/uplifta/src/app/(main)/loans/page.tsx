"use client";

import { PageHeader } from "@/components/ui/page-header";
import { DataTable } from "@/components/data/DataTable";
import { ColumnDef } from "@tanstack/react-table";
import { useLoans } from "@/hooks/useLoans";
import { RepaymentModal } from "@/components/modals/RepaymentModal";

type Row = {
  id: string;
  clientName: string;
  balance: number;
  nextDueDate?: string;
  status: string;
};

const columns: ColumnDef<Row>[] = [
  { accessorKey: "id", header: "Account #" },
  { accessorKey: "clientName", header: "Client" },
  {
    accessorKey: "balance",
    header: "Balance",
    cell: ({ row }) => new Intl.NumberFormat("en-UG", { style: "currency", currency: "UGX", maximumFractionDigits: 0 }).format(row.original.balance),
  },
  { accessorKey: "nextDueDate", header: "Next Due", cell: ({ getValue }) => (getValue<string | undefined>()?.slice(0, 10) || "-") },
  { accessorKey: "status", header: "Status" },
];

export default function LoansPage() {
  const { data = [], isLoading } = useLoans();
  const rows: Row[] = data.map((l) => ({ id: l.id, clientName: l.clientName, balance: l.balance, nextDueDate: l.nextDueDate, status: l.status }));

  return (
    <div className="space-y-4">
      <PageHeader title="Loans" subtitle="Active loan accounts" actions={<RepaymentModal />} />
      <DataTable columns={columns} data={rows} isLoading={isLoading} />
    </div>
  );
}


