"use client";

import { useForm } from "react-hook-form";
import { z } from "zod";
import { zodResolver } from "@hookform/resolvers/zod";
import { Modal, ModalContent, ModalTrigger, ModalClose } from "@/components/ui/modal";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { FormField } from "@/components/ui/form";
import { DatePicker } from "@/components/ui/date-picker";

const schema = z.object({
  amount: z.number().min(1),
  paidAt: z.date(),
  reference: z.string().optional(),
});
type Values = z.infer<typeof schema>;

export function RepaymentModal({ onSubmit }: { onSubmit?: (v: Values) => Promise<void> | void }) {
  const form = useForm<Values>({ resolver: zodResolver(schema), defaultValues: { amount: 0, paidAt: new Date(), reference: "" } });

  const handleSubmit = form.handleSubmit(async (values) => {
    await onSubmit?.(values);
    form.reset({ amount: 0, paidAt: new Date(), reference: "" });
  });

  return (
    <Modal>
      <ModalTrigger asChild>
        <Button size="sm">Record Repayment</Button>
      </ModalTrigger>
      <ModalContent>
        <div className="space-y-4">
          <h2 className="text-lg font-semibold">Repayment</h2>
          <form onSubmit={handleSubmit} className="space-y-4">
            <FormField label="Amount" error={form.formState.errors.amount?.message}>
              <Input type="number" step="0.01" {...form.register("amount", { valueAsNumber: true })} />
            </FormField>
            <FormField label="Paid at" error={form.formState.errors.paidAt?.message as string | undefined}>
              <DatePicker value={form.watch("paidAt")} onChange={(d) => form.setValue("paidAt", d || new Date())} />
            </FormField>
            <FormField label="Reference" error={form.formState.errors.reference?.message}>
              <Input placeholder="Optional reference" {...form.register("reference")} />
            </FormField>
            <div className="flex justify-end gap-2">
              <ModalClose asChild>
                <Button type="button" variant="outline">Cancel</Button>
              </ModalClose>
              <Button type="submit">Save</Button>
            </div>
          </form>
        </div>
      </ModalContent>
    </Modal>
  );
}


