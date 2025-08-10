export default function ClientProfilePage({ params }: { params: { id: string } }) {
  return (
    <div className="space-y-4">
      <h1 className="text-xl font-semibold">Client #{params.id}</h1>
      <div className="grid gap-4 md:grid-cols-2">
        <div className="rounded-md border p-4 text-sm text-muted-foreground">KYC documents tab placeholder</div>
        <div className="rounded-md border p-4 text-sm text-muted-foreground">Loan history tab placeholder</div>
      </div>
    </div>
  );
}


