"use client";

import { useRouter } from "next/navigation";
import { useLoanProducts, useUpdateLoanProduct } from "@/hooks/useLoanProducts";
import { useForm } from "react-hook-form";
import { z } from "zod";
import { zodResolver } from "@hookform/resolvers/zod";
import { PageHeader } from "@/components/ui/page-header";
import { FormField } from "@/components/ui/form";
import { Input } from "@/components/ui/input";
import { Button } from "@/components/ui/button";
import { toast } from "sonner";

const schema = z.object({
  name: z.string().min(2),
  interestRateAnnualPct: z.number().min(0),
  maxAmount: z.number().min(0),
  minTermMonths: z.number().min(1),
  maxTermMonths: z.number().min(1),
});
type Values = z.infer<typeof schema>;

export default function EditProductPage({ params }: { params: { id: string } }) {
  const router = useRouter();
  const { data = [] } = useLoanProducts();
  const product = data.find((p) => p.id === params.id);
  const mutation = useUpdateLoanProduct();
  const form = useForm<Values>({
    resolver: zodResolver(schema),
    values: product || { name: "", interestRateAnnualPct: 0, maxAmount: 0, minTermMonths: 1, maxTermMonths: 12 },
  });

  const onSubmit = form.handleSubmit(async (values) => {
    await mutation.mutateAsync({ id: params.id, input: values });
    toast.success("Product updated");
    router.push("/loan-products");
  });

  if (!product) return <div className="text-sm text-muted-foreground">Loading...</div>;

  return (
    <div className="space-y-4">
      <PageHeader title={`Edit ${product.name}`} />
      <form onSubmit={onSubmit} className="grid gap-4 sm:grid-cols-2">
        <FormField label="Name" error={form.formState.errors.name?.message}>
          <Input {...form.register("name")} />
        </FormField>
        <FormField label="Rate (p.a. %)" error={form.formState.errors.interestRateAnnualPct?.message}>
          <Input type="number" step="0.01" {...form.register("interestRateAnnualPct", { valueAsNumber: true })} />
        </FormField>
        <FormField label="Max Amount" error={form.formState.errors.maxAmount?.message}>
          <Input type="number" {...form.register("maxAmount", { valueAsNumber: true })} />
        </FormField>
        <FormField label="Min Term (months)" error={form.formState.errors.minTermMonths?.message}>
          <Input type="number" {...form.register("minTermMonths", { valueAsNumber: true })} />
        </FormField>
        <FormField label="Max Term (months)" error={form.formState.errors.maxTermMonths?.message}>
          <Input type="number" {...form.register("maxTermMonths", { valueAsNumber: true })} />
        </FormField>
        <div className="sm:col-span-2 flex justify-end gap-2">
          <Button variant="outline" type="button" onClick={() => router.back()}>Cancel</Button>
          <Button type="submit">Save</Button>
        </div>
      </form>
    </div>
  );
}


