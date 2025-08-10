"use client";

import { PageHeader } from "@/components/ui/page-header";
import { DataTable } from "@/components/data/DataTable";
import { ColumnDef } from "@tanstack/react-table";
import { useDisbursements, useMarkDisbursementPaid } from "@/hooks/useDisbursements";
import { MarkPaidModal } from "@/components/modals/MarkPaidModal";
import { toast } from "sonner";

type Row = { id: string; clientName: string; amount: number; scheduledDate: string; paid: boolean };

const columns: ColumnDef<Row>[] = [
  { accessorKey: "id", header: "ID" },
  { accessorKey: "clientName", header: "Client" },
  { accessorKey: "amount", header: "Amount", cell: ({ row }) => new Intl.NumberFormat("en-UG", { style: "currency", currency: "UGX", maximumFractionDigits: 0 }).format(row.original.amount) },
  { accessorKey: "scheduledDate", header: "Scheduled", cell: ({ getValue }) => getValue<string>().slice(0, 10) },
  { accessorKey: "paid", header: "Status", cell: ({ getValue }) => (getValue<boolean>() ? "Paid" : "Pending") },
];

export default function DisbursementsPage() {
  const { data = [], isLoading } = useDisbursements();
  const mutation = useMarkDisbursementPaid();
  const rows: Row[] = data.map((d) => ({ id: d.id, clientName: d.clientName, amount: d.amount, scheduledDate: d.scheduledDate, paid: d.paid }));
  return (
    <div className="space-y-4">
      <PageHeader title="Disbursements" subtitle="Pending and completed disbursements" />
      <DataTable columns={[
        ...columns,
        {
          id: "actions",
          header: "Actions",
          cell: ({ row }) => row.original.paid ? null : (
            <MarkPaidModal
              trigger={<button className="text-primary">Mark as paid</button>}
              onSubmit={async (v) => {
                await mutation.mutateAsync({ id: row.original.id, reference: v.reference });
                toast.success("Marked as paid");
              }}
            />
          )
        }
      ]} data={rows} isLoading={isLoading} />
    </div>
  );
}


