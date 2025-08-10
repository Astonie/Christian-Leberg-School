export default function LoanDetailsPage({ params }: { params: { id: string } }) {
  return (
    <div className="space-y-4">
      <h1 className="text-xl font-semibold">Loan #{params.id}</h1>
      <div className="rounded-md border p-4 text-sm text-muted-foreground">Repayment schedule placeholder</div>
    </div>
  );
}


