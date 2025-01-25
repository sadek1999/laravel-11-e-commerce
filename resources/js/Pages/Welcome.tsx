import Navbar from "@/Components/App/Navbar";
import ProductItem from "@/Components/App/ProductItem";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { PageProps } from "@/types";

import { PaginationProps } from "@/types";




export type TProduct={
  id:number,
  title:string,
  slug:string,
  price:number,
  quantity:number,
  image:string,
  user:{
    id:number,
    name:string,
  },
  department:{
    id:number,
    name:string,
  }
}

export default function welcome({
  products
}: PageProps<{products:PaginationProps<TProduct>}>) {

  console.log(products)
  return (
    <>
      <AuthenticatedLayout>
<div>

{
  products?.map(product=><ProductItem product={product}></ProductItem>)
}

</div>
      </AuthenticatedLayout>
    </>
  );
}
